<?php

namespace App\Http\Controllers\Admin;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Result;
use App\Models\Answer;
use App\Services\ClaudeService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class QuizController extends Controller
{
    protected $claudeService;

    public function __construct(ClaudeService $claudeService)
    {
        $this->claudeService = $claudeService;
    }

    /**
     * Check if user can manage quizzes (teacher or admin only)
     */
    private function authorizeQuizManagement()
    {
        if (!Auth::check() || (Auth::user()->user_type === 'student' && !Auth::user()->is_admin)) {
            abort(403, 'غير مصرح لك بإدارة الاختبارات. هذه الصفحة للمعلمين فقط.');
        }
    }

    public function index()
    {
        $this->authorizeQuizManagement();
        $quizzes = Quiz::where('user_id', Auth::id())
            ->with(['questions', 'subject'])
            ->latest()
            ->get();

        // Define roots array for view usage
        $roots = [
            'jawhar' => ['name' => 'جَوهر', 'icon' => '🎯', 'color' => 'red'],
            'zihn' => ['name' => 'ذِهن', 'icon' => '🧠', 'color' => 'cyan'],
            'waslat' => ['name' => 'وَصلات', 'icon' => '🔗', 'color' => 'yellow'],
            'roaya' => ['name' => 'رُؤية', 'icon' => '👁️', 'color' => 'purple']
        ];

        return view('quizzes.index', compact('quizzes', 'roots'));
    }

    public function create()
    {
        $this->authorizeQuizManagement();
        $subjects = Subject::active()->ordered()->get();
        return view('quizzes.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $this->authorizeQuizManagement();

        // Updated validation to use subject_id instead of subject
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id', // Changed from 'subject'
            'grade_level' => 'required|integer|min:1|max:9',
            'creation_method' => 'required|in:ai,manual,hybrid',
            'roots' => 'required_if:creation_method,ai,hybrid|array',
            'topic' => 'required_if:creation_method,ai,hybrid|string|max:255',
            'include_passage' => 'boolean',
            'passage_topic' => 'nullable|string|max:255',
            'educational_text' => 'nullable|string',
            'shuffle_questions' => 'nullable',
            'shuffle_answers' => 'nullable',
            'show_results' => 'nullable',
            'time_limit' => 'nullable|integer|min:0|max:180',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'text_source' => 'nullable|string',
            'text_type' => 'nullable|string',
            'text_length' => 'nullable|string'
        ]);

        Log::info('Quiz store request', [
            'method' => $request->method(),
            'creation_method' => $validated['creation_method'] ?? 'not set',
            'subject_id' => $validated['subject_id'],
            'is_ajax' => $request->ajax(),
            'expects_json' => $request->expectsJson()
        ]);

        DB::beginTransaction();

        try {
            // Transform roots structure for database storage AND for ClaudeService
            $settingsForDb = [];
            $settingsForClaude = [];

            if (isset($validated['roots'])) {
                foreach ($validated['roots'] as $rootKey => $root) {
                    $settingsForClaude[$rootKey] = [];
                    $levelsForDb = [];

                    foreach ($root['levels'] ?? [] as $level => $levelData) {
                        if (isset($levelData['count']) && $levelData['count'] > 0) {
                            // For database storage
                            $levelsForDb[] = [
                                'depth' => $levelData['depth'] ?? $level,
                                'count' => (int) $levelData['count']
                            ];

                            // For Claude service (simple key => value)
                            $depthKey = $levelData['depth'] ?? $level;
                            $settingsForClaude[$rootKey][$depthKey] = (int) $levelData['count'];
                        }
                    }

                    if (!empty($levelsForDb)) {
                        $settingsForDb[$rootKey] = $levelsForDb;
                    }
                }
            }

            $quiz = Quiz::create([
                'user_id' => Auth::id(),
                'title' => $validated['title'],
                'subject_id' => $validated['subject_id'], // Changed from 'subject'
                'grade_level' => $validated['grade_level'],
                'pin' => strtoupper(Str::random(6)),
                'shuffle_questions' => isset($validated['shuffle_questions']) && $validated['shuffle_questions'] == '1',
                'shuffle_answers' => isset($validated['shuffle_answers']) && $validated['shuffle_answers'] == '1',
                'show_results' => !isset($validated['show_results']) || $validated['show_results'] == '1',
                'time_limit' => $validated['time_limit'] ?? null,
                'passing_score' => $validated['passing_score'] ?? 60,
                'settings' => [
                    'creation_method' => $validated['creation_method'],
                    'question_count' => $validated['question_count'] ?? 0,
                    'time_limit' => $validated['time_limit'] ?? null,
                    'allow_review' => $validated['allow_review'] ?? false,
                    'roots' => $settingsForDb
                ]
            ]);

            // AI Generation logic
            if ($validated['creation_method'] === 'ai') {
                // Get subject name for AI generation
                $subject = Subject::find($validated['subject_id']);
                $subjectName = $subject ? $subject->name : 'عام';

                $aiResponse = $this->claudeService->generateJuzoorQuiz(
                    $subjectName, // Use subject name instead of slug
                    $quiz->grade_level,
                    $validated['topic'],
                    $settingsForClaude,
                    isset($validated['include_passage']) && $validated['include_passage'],
                    $validated['passage_topic'] ?? null
                );

                $this->parseAndSaveQuestions($quiz, $aiResponse);
            }

            DB::commit();

            return redirect()->route('quizzes.show', $quiz)
                ->with('success', 'تم إنشاء الاختبار بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Quiz creation failed', ['error' => $e->getMessage()]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'فشل في إنشاء الاختبار: ' . $e->getMessage());
        }
    }

    public function show(Quiz $quiz)
    {
        $this->authorizeQuizManagement();

        // Allow admin to view any quiz
        if (!Auth::user()->is_admin && (int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        $quiz->load(['questions', 'subject']);
        return view('quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        $this->authorizeQuizManagement();

        // Allow admin to edit any quiz
        if (!Auth::user()->is_admin && (int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        $subjects = Subject::active()->ordered()->get();

        if ($quiz->has_submissions) {
            return redirect()->route('quizzes.show', $quiz)
                ->with('error', 'لا يمكن تعديل الاختبار بعد أن بدأ الطلاب في حله.');
        }

        return view('quizzes.edit', compact('quiz', 'subjects'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $this->authorizeQuizManagement();

        // Allow admin to update any quiz
        if (!Auth::user()->is_admin && (int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        if ($quiz->has_submissions) {
            return redirect()->route('quizzes.show', $quiz)
                ->with('error', 'لا يمكن تعديل الاختبار بعد أن بدأ الطلاب في حله.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id', // Changed from 'subject'
            'grade_level' => 'required|integer|min:1|max:9',
            'description' => 'nullable|string',
            'time_limit' => 'nullable|integer|min:1',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'show_results' => 'boolean'
        ]);

        $quiz->update($validated);

        // Update passage if provided
        if ($request->has('passage')) {
            $firstQuestion = $quiz->questions()->first();
            if ($firstQuestion) {
                $firstQuestion->update([
                    'passage' => $request->input('passage'),
                    'passage_title' => $request->input('passage_title')
                ]);
            }
        }

        return redirect()->route('quizzes.show', $quiz)
            ->with('success', 'تم تحديث الاختبار بنجاح.');
    }

    // ... other methods remain the same ...

    /**
     * Parse AI response and save questions to database
     */
    private function parseAndSaveQuestions(Quiz $quiz, array $aiResponse)
    {
        if (!isset($aiResponse['questions']) || !is_array($aiResponse['questions'])) {
            throw new \Exception('Invalid AI response format');
        }

        $passage = $aiResponse['passage'] ?? null;
        $passageTitle = $aiResponse['passage_title'] ?? null;

        foreach ($aiResponse['questions'] as $index => $questionData) {
            if (!isset($questionData['question']) || !isset($questionData['options'])) {
                continue;
            }

            $options = is_array($questionData['options']) ?
                $questionData['options'] :
                json_decode($questionData['options'], true);

            Question::create([
                'quiz_id' => $quiz->id,
                'question' => $questionData['question'],
                'root_type' => $questionData['root_type'] ?? 'jawhar',
                'depth_level' => (int) ($questionData['depth_level'] ?? 1),
                'options' => $options,
                'correct_answer' => $questionData['correct_answer'],
                'passage' => $index === 0 ? $passage : null,
                'passage_title' => $index === 0 ? $passageTitle : null,
            ]);
        }
    }
}