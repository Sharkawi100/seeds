<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Result;
use App\Models\Answer;
use App\Models\Subject;
use App\Services\ClaudeService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


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

    /**
     * Check if current user owns the quiz
     */
    private function authorizeQuizOwnership(Quiz $quiz)
    {
        if (!Auth::check()) {
            abort(401, 'يجب تسجيل الدخول أولاً.');
        }

        if (!Auth::user()->is_admin && (int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }
    }

    /**
     * Display a listing of quizzes
     */
    public function index()
    {
        $this->authorizeQuizManagement();

        $quizzes = Quiz::where('user_id', Auth::id())
            ->with(['questions', 'subject', 'results']) // ← Just add 'results' here
            ->latest()
            ->get();

        // Keep your roots array - it's still good practice
        $roots = [
            'jawhar' => ['name' => 'جَوهر', 'icon' => '🎯', 'color' => 'red'],
            'zihn' => ['name' => 'ذِهن', 'icon' => '🧠', 'color' => 'cyan'],
            'waslat' => ['name' => 'وَصلات', 'icon' => '🔗', 'color' => 'yellow'],
            'roaya' => ['name' => 'رُؤية', 'icon' => '👁️', 'color' => 'purple']
        ];

        return view('quizzes.index', compact('quizzes', 'roots'));
    }

    /**
     * Show the form for creating a new quiz
     */
    public function create()
    {
        $this->authorizeQuizManagement();

        // ✅ Cleanup old incomplete quizzes for current user
        $deletedCount = Quiz::where('user_id', Auth::id())
            ->where('created_at', '<', now()->subHours(6)) // Older than 6 hours
            ->whereDoesntHave('questions') // No questions added
            ->delete();

        // Log cleanup activity
        if ($deletedCount > 0) {
            Log::info('Cleaned up incomplete quizzes', [
                'user_id' => Auth::id(),
                'deleted_count' => $deletedCount
            ]);
        }

        $subjects = Subject::active()->ordered()->get();
        return view('quizzes.create', compact('subjects'));
    }

    /**
     * WIZARD STEP 1: Create quiz with basic information
     */
    public function createStep1(Request $request)
    {
        $this->authorizeQuizManagement();

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'grade_level' => 'required|integer|min:1|max:9',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            Log::error('Quiz Step 1 validation failed', [
                'errors' => $validator->errors()->toArray(),
                'request_data' => $request->all(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'debug_data' => [
                    'request_fields' => array_keys($request->all()),
                    'validation_rules' => [
                        'title' => 'required|string|max:255',
                        'subject_id' => 'required|exists:subjects,id',
                        'grade_level' => 'required|integer|min:1|max:9',
                        'description' => 'nullable|string|max:1000',
                    ]
                ]
            ], 422);
        }

        try {
            DB::beginTransaction();

            $quiz = Quiz::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'subject_id' => $request->subject_id,
                'grade_level' => $request->grade_level,
                'description' => $request->description,
                'pin' => strtoupper(Str::random(6)),
                'is_active' => false, // Keep inactive until finalized
                'settings' => [
                    'step' => 1,
                    'creation_method' => null,
                ],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'quiz_id' => $quiz->id,
                'message' => 'تم حفظ المعلومات الأساسية بنجاح'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Quiz Step 1 creation failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ البيانات'
            ], 500);
        }
    }

    /**
     * WIZARD STEP 2: Update quiz creation method
     */
    public function updateMethod(Request $request, Quiz $quiz)
    {
        $this->authorizeQuizManagement();
        $this->authorizeQuizOwnership($quiz);

        $validator = Validator::make($request->all(), [
            'creation_method' => 'required|in:ai,manual,hybrid',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $settings = $quiz->settings ?? [];
            $settings['step'] = 2;
            $settings['creation_method'] = $request->creation_method;

            $quiz->update(['settings' => $settings]);

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ طريقة الإنشاء بنجاح'
            ]);

        } catch (\Exception $e) {
            Log::error('Quiz method update failed', [
                'error' => $e->getMessage(),
                'quiz_id' => $quiz->id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ طريقة الإنشاء'
            ], 500);
        }
    }

    /**
     * WIZARD STEP 3B: Generate questions using AI
     */
    public function generateQuestions(Request $request, Quiz $quiz)
    {
        // 🔍 DEBUG: Comprehensive logging
        Log::info('generateQuestions method called', [
            'quiz_id' => $quiz->id,
            'quiz_title' => $quiz->title,
            'request_data' => $request->all(),
            'text_source' => $request->text_source,
            'has_educational_text' => !empty($request->educational_text),
            'educational_text_length' => strlen($request->educational_text ?? ''),
            'educational_text_preview' => substr($request->educational_text ?? '', 0, 100),
            'user_id' => Auth::id(),
            'user_can_use_ai' => Auth::user()->canUseAI(),
            'current_passage_data' => $quiz->passage_data
        ]);

        $this->authorizeQuizManagement();
        $this->authorizeQuizOwnership($quiz);

        // Handle subscription checks for AI features
        if ($request->text_source === 'none' && !Auth::user()->canUseAI()) {
            Log::info('Subscription required for no-text generation', ['user_id' => Auth::id()]);
            return response()->json([
                'success' => false,
                'message' => 'يتطلب إنشاء اختبار بدون نص اشتراك نشط',
                'upgrade_required' => true,
                'redirect' => route('subscription.upgrade')
            ], 403);
        }

        // Handle manual text for non-subscribers
        if ($request->text_source === 'manual' && !Auth::user()->canUseAI()) {
            Log::info('Manual text processing for non-subscriber', [
                'quiz_id' => $quiz->id,
                'has_text' => !empty($request->educational_text),
                'text_length' => strlen($request->educational_text ?? ''),
                'topic' => $request->topic
            ]);

            // Save manual text to quiz passage_data
            $passageData = [
                'passage' => $request->educational_text ?? '',
                'passage_title' => $request->topic ?? 'نص القراءة',
                'saved_at' => now()->toISOString(),
                'text_source' => 'manual'
            ];

            try {
                $quiz->update(['passage_data' => $passageData]);
                Log::info('Manual text saved successfully', [
                    'quiz_id' => $quiz->id,
                    'passage_data' => $passageData
                ]);

                // Verify the save worked
                $quiz->refresh();
                $savedData = $quiz->passage_data;
                Log::info('Verification of saved data', [
                    'quiz_id' => $quiz->id,
                    'saved_passage_data' => $savedData,
                    'save_successful' => !empty($savedData['passage'])
                ]);

            } catch (\Exception $e) {
                Log::error('Failed to save manual text', [
                    'quiz_id' => $quiz->id,
                    'error' => $e->getMessage(),
                    'passage_data' => $passageData
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ النص. يمكنك الآن إضافة الأسئلة يدوياً',
                'redirect' => route('quizzes.questions.create', $quiz),
                'skip_ai' => true,
                'saved_text_length' => strlen($request->educational_text ?? ''),
                'debug_info' => [
                    'text_saved' => true,
                    'quiz_id' => $quiz->id,
                    'passage_data' => $passageData
                ]
            ]);
        }

        // Validation for AI-powered generation
        $validator = Validator::make($request->all(), [
            'topic' => 'required|string|max:255',
            'question_count' => 'required|integer|min:4|max:30',
            'educational_text' => 'nullable|required_unless:text_source,none|string|min:50',
            'text_source' => 'required|in:ai,manual,none',
            'roots' => 'required|array',
            'roots.*' => 'integer|min:0|max:20',
            // Quiz Configuration Settings
            'time_limit' => 'nullable|integer|min:1|max:180',
            'passing_score' => 'required|integer|min:0|max:100',
            'shuffle_questions' => 'boolean',
            'shuffle_answers' => 'boolean',
            'show_results' => 'boolean',
            'activate_quiz' => 'boolean',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed in generateQuestions', [
                'errors' => $validator->errors(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'بيانات غير صحيحة'
            ], 422);
        }

        try {
            // Validate root distribution matches question count
            $totalRootQuestions = array_sum($request->roots);
            if ($totalRootQuestions != $request->question_count) {
                throw new \Exception('مجموع توزيع الأسئلة على الجذور لا يتطابق مع العدد الإجمالي');
            }

            // Check AI subscription for AI features
            if (($request->text_source === 'ai' || $request->text_source === 'none') && !Auth::user()->canUseAI()) {
                return response()->json([
                    'success' => false,
                    'message' => 'يتطلب توليد الاختبارات بالذكاء الاصطناعي اشتراك نشط',
                    'upgrade_required' => true
                ], 403);
            }

            // Get subject information
            $subject = Subject::find($quiz->subject_id);
            $subjectName = $subject ? $subject->name : 'عام';

            // Prepare educational text based on source
            $educationalText = '';
            if ($request->text_source === 'manual' || $request->text_source === 'ai') {
                $educationalText = $request->educational_text ?? '';
                Log::info('Educational text prepared', [
                    'text_source' => $request->text_source,
                    'text_length' => strlen($educationalText),
                    'text_preview' => substr($educationalText, 0, 100)
                ]);
            }

            // Prepare roots structure for AI
            $rootsForAI = $this->prepareRootsForAI($request->roots);
            $totalRequested = array_sum($request->roots);

            Log::info('Roots transformation completed', [
                'original_roots' => $request->roots,
                'transformed_roots' => $rootsForAI,
                'total_requested' => $totalRequested,
                'total_transformed' => array_sum(array_map('array_sum', $rootsForAI))
            ]);

            // Generate questions based on text source
            $aiResponse = $this->generateQuestionsBasedOnSource(
                $request->text_source,
                $educationalText,
                $subjectName,
                $quiz->grade_level,
                $request->topic,
                $rootsForAI
            );

            // Clear existing questions before saving new ones
            $quiz->questions()->delete();
            Log::info('Existing questions cleared', ['quiz_id' => $quiz->id]);

            // Save questions to database
            $questionsCount = $this->parseAndSaveQuestions($quiz, $aiResponse);

            // Update quiz settings and configuration
            $this->updateQuizConfiguration($quiz, $request, $educationalText);

            Log::info('Questions generated successfully', [
                'quiz_id' => $quiz->id,
                'questions_count' => $questionsCount,
                'text_source' => $request->text_source,
                'quiz_activated' => $request->activate_quiz ?? false
            ]);

            return response()->json([
                'success' => true,
                'questions' => $aiResponse['questions'] ?? [],
                'questions_count' => $questionsCount,
                'quiz_activated' => $request->activate_quiz ?? false,
                'passage_saved' => !empty($aiResponse['passage']),
                'message' => 'تم إنشاء الأسئلة وحفظ الإعدادات بنجاح'
            ]);

        } catch (\Exception $e) {
            Log::error('Question generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'quiz_id' => $quiz->id,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء الأسئلة: ' . $e->getMessage(),
                'debug_info' => [
                    'quiz_id' => $quiz->id,
                    'text_source' => $request->text_source,
                    'error_type' => get_class($e)
                ]
            ], 500);
        }
    }

    /**
     * Prepare roots structure for AI generation
     */
    private function prepareRootsForAI(array $roots): array
    {
        $rootsForAI = [];

        foreach ($roots as $rootKey => $count) {
            if ($count > 0) {
                if ($count <= 2) {
                    // For small counts, put everything in level 1
                    $rootsForAI[$rootKey] = [
                        '1' => $count,
                        '2' => 0,
                        '3' => 0
                    ];
                } else {
                    // For larger counts, distribute across levels
                    $level1 = floor($count * 0.4);
                    $level2 = floor($count * 0.4);
                    $level3 = $count - $level1 - $level2; // Remainder goes to level 3

                    $rootsForAI[$rootKey] = [
                        '1' => $level1,
                        '2' => $level2,
                        '3' => $level3
                    ];
                }
            }
        }

        return $rootsForAI;
    }

    /**
     * Generate questions based on text source
     */
    private function generateQuestionsBasedOnSource(
        string $textSource,
        string $educationalText,
        string $subjectName,
        int $gradeLevel,
        string $topic,
        array $rootsForAI
    ): array {

        if ($textSource === 'none') {
            // Generate complete quiz with passage from scratch
            Log::info('Generating complete quiz without existing text');

            // Log AI usage
            $quota = \App\Models\MonthlyQuota::getOrCreateCurrent(Auth::id());
            $quota->incrementAiQuizRequests();

            return $this->claudeService->generateJuzoorQuiz(
                $subjectName,
                $gradeLevel,
                $topic,
                $rootsForAI,
                true, // include passage
                $topic // passage topic
            );

        } else {
            // Generate questions from existing text (manual or AI-generated)
            Log::info('Generating questions from existing text', [
                'text_length' => strlen($educationalText),
                'text_source' => $textSource
            ]);

            if (empty($educationalText)) {
                throw new \Exception('النص التعليمي مطلوب لتوليد الأسئلة');
            }

            // Log AI usage for question generation
            $quota = \App\Models\MonthlyQuota::getOrCreateCurrent(Auth::id());
            $quota->incrementAiQuizRequests();

            $questions = $this->claudeService->generateQuestionsFromText(
                $educationalText,
                $subjectName,
                $gradeLevel,
                $rootsForAI
            );

            return [
                'questions' => $questions,
                'passage' => $educationalText,
                'passage_title' => $topic
            ];
        }
    }

    /**
     * Update quiz configuration and settings
     */
    private function updateQuizConfiguration(Quiz $quiz, Request $request, string $educationalText): void
    {
        $settings = $quiz->settings ?? [];
        $settings['step'] = '3b';
        $settings['question_count'] = $request->question_count;
        $settings['roots_distribution'] = $request->roots;
        $settings['questions_generated'] = true;
        $settings['generation_completed_at'] = now()->toISOString();

        if (!empty($educationalText)) {
            $settings['final_educational_text'] = $educationalText;
        }

        $quiz->update([
            'settings' => $settings,
            'time_limit' => $request->time_limit,
            'passing_score' => $request->passing_score ?? 60,
            'shuffle_questions' => $request->shuffle_questions ?? false,
            'shuffle_answers' => $request->shuffle_answers ?? false,
            'show_results' => $request->show_results ?? true,
            'is_active' => $request->activate_quiz ?? false,
        ]);

        Log::info('Quiz configuration updated', [
            'quiz_id' => $quiz->id,
            'settings' => $settings,
            'is_active' => $request->activate_quiz ?? false
        ]);
    }

    /**
     * WIZARD STEP 4: Finalize quiz
     */
    public function finalizeQuiz(Request $request, Quiz $quiz)
    {
        $this->authorizeQuizManagement();
        $this->authorizeQuizOwnership($quiz);

        try {
            // Validate quiz has questions
            if ($quiz->questions()->count() === 0) {
                throw new \Exception('لا يمكن إنهاء الاختبار بدون أسئلة');
            }

            // Activate the quiz
            $quiz->update([
                'is_active' => true,
                'settings' => array_merge($quiz->settings ?? [], [
                    'step' => 'completed',
                    'finalized_at' => now()->toISOString()
                ])
            ]);

            return response()->json([
                'success' => true,
                'quiz_id' => $quiz->id,
                'quiz_pin' => $quiz->pin,
                'redirect' => route('quizzes.show', $quiz),
                'message' => 'تم إنهاء الاختبار بنجاح وهو جاهز للاستخدام'
            ]);

        } catch (\Exception $e) {
            Log::error('Quiz finalization failed', [
                'error' => $e->getMessage(),
                'quiz_id' => $quiz->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنهاء الاختبار: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created quiz in storage (fallback method)
     */
    public function store(Request $request)
    {
        $this->authorizeQuizManagement();
        // quota check here:
        if (Auth::user()->hasReachedQuizLimit()) {
            $limits = Auth::user()->getCurrentQuotaLimits();
            return redirect()->back()->with(
                'error',
                "لقد وصلت للحد الأقصى الشهري ({$limits['monthly_quiz_limit']} اختبار). يرجى الانتظار حتى الشهر القادم أو ترقية الاشتراك."
            );
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'grade_level' => 'required|integer|min:1|max:9',
            'description' => 'nullable|string|max:1000',
            'creation_method' => 'nullable|in:ai,manual,hybrid',
            'shuffle_questions' => 'boolean',
            'shuffle_answers' => 'boolean',
            'time_limit' => 'nullable|integer|min:0|max:180',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'max_attempts' => 'nullable|integer|min:1|max:10',
            'scoring_method' => 'required|in:latest,average,highest,first_only',
            'show_results' => 'boolean',
        ]);

        Log::info('Direct quiz store request (fallback)', [
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'creation_method' => $validated['creation_method'] ?? 'manual'
        ]);

        DB::beginTransaction();

        try {
            // Create quiz with direct method
            $quiz = Quiz::create([
                'user_id' => Auth::id(),
                'title' => $validated['title'],
                'subject_id' => $validated['subject_id'],
                'grade_level' => $validated['grade_level'],
                'description' => $validated['description'] ?? null,
                'pin' => strtoupper(Str::random(6)),
                'shuffle_questions' => $validated['shuffle_questions'] ?? false,
                'shuffle_answers' => $validated['shuffle_answers'] ?? false,
                'time_limit' => $validated['time_limit'] ?? null,
                'passing_score' => $validated['passing_score'] ?? 60,
                'max_attempts' => $validated['max_attempts'],
                'scoring_method' => $validated['scoring_method'],
                'show_results' => $validated['show_results'] ?? true,
                'is_active' => false, // Will be activated after questions are added
                'settings' => [
                    'creation_method' => $validated['creation_method'] ?? 'manual',
                    'question_count' => 0,
                    'step' => 'completed',
                ],
            ]);

            // Increment quota counter
            $quota = \App\Models\MonthlyQuota::getOrCreateCurrent(Auth::id());
            $quota->incrementQuizCount();

            DB::commit();

            return redirect()->route('quizzes.questions.create', $quiz)
                ->with('success', 'تم إنشاء الاختبار بنجاح. الآن أضف الأسئلة.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Direct quiz creation failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'request_data' => $request->except(['_token'])
            ]);

            return redirect()->route('quizzes.create')
                ->with('error', 'حدث خطأ أثناء إنشاء الاختبار: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified quiz
     */
    public function show(Quiz $quiz)
    {
        $this->authorizeQuizManagement();
        $this->authorizeQuizOwnership($quiz);

        $quiz->load(['questions', 'subject', 'results']);

        return view('quizzes.show', compact('quiz'));
    }

    /**
     * Show the form for editing the specified quiz
     */
    public function edit(Quiz $quiz)
    {
        $this->authorizeQuizManagement();
        $this->authorizeQuizOwnership($quiz);

        if ($quiz->has_submissions) {
            return redirect()->route('quizzes.show', $quiz)
                ->with('error', 'لا يمكن تعديل الاختبار بعد أن بدأ الطلاب في حله.');
        }

        $subjects = Subject::active()->ordered()->get();

        return view('quizzes.edit', compact('quiz', 'subjects'));
    }

    /**
     * Update the specified quiz
     */
    public function update(Request $request, Quiz $quiz)
    {
        $this->authorizeQuizManagement();
        $this->authorizeQuizOwnership($quiz);

        if ($quiz->has_submissions) {
            return redirect()->route('quizzes.show', $quiz)
                ->with('error', 'لا يمكن تعديل الاختبار بعد أن بدأ الطلاب في حله.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'grade_level' => 'required|integer|min:1|max:9',
            'description' => 'nullable|string|max:1000',
            'time_limit' => 'nullable|integer|min:1|max:180',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'show_results' => 'boolean',
            'shuffle_questions' => 'boolean',
            'shuffle_answers' => 'boolean',
            'max_attempts' => 'nullable|integer|min:1|max:10',
            'scoring_method' => 'required|in:latest,average,highest,first_only',
        ]);

        $quiz->update($validated);

        // Update passage if provided
        if ($request->has('passage')) {
            $firstQuestion = $quiz->questions()->first();
            if ($firstQuestion) {
                $firstQuestion->update([
                    'passage' => $request->input('passage'),
                    'passage_title' => $request->input('passage_title'),
                ]);
            }
        }

        return redirect()->route('quizzes.show', $quiz)
            ->with('success', 'تم تحديث الاختبار بنجاح.');
    }

    /**
     * Remove the specified quiz from storage
     */
    public function destroy(Quiz $quiz)
    {
        $this->authorizeQuizManagement();
        $this->authorizeQuizOwnership($quiz);

        $quizTitle = $quiz->title;
        $quiz->delete();

        return redirect()->route('quizzes.index')
            ->with('success', "تم حذف الاختبار '{$quizTitle}' بنجاح.");
    }

    public function attemptAnalytics(Quiz $quiz)
    {
        $this->authorizeQuizOwnership($quiz);

        $analytics = DB::table('results')
            ->leftJoin('users', 'results.user_id', '=', 'users.id')
            ->where('results.quiz_id', $quiz->id)
            ->whereNotNull('results.user_id')
            ->selectRaw('
                results.user_id,
                users.name,
                COUNT(*) as total_attempts,
                MIN(results.total_score) as lowest_score,
                MAX(results.total_score) as highest_score,
                AVG(results.total_score) as average_score,
                MAX(results.created_at) as latest_attempt
            ')
            ->groupBy('results.user_id', 'users.name')
            ->orderBy('total_attempts', 'desc')
            ->get();

        return view('results.quiz-results', compact('quiz', 'analytics'));
    }

    /**
     * Duplicate a quiz
     */
    public function duplicate(Quiz $quiz)
    {
        $this->authorizeQuizManagement();
        $this->authorizeQuizOwnership($quiz);

        DB::beginTransaction();
        try {
            // Clone quiz
            $newQuiz = $quiz->replicate();
            $newQuiz->title = $quiz->title . ' (نسخة)';
            $newQuiz->pin = strtoupper(Str::random(6));
            $newQuiz->has_submissions = false;
            $newQuiz->is_active = true;
            $newQuiz->save();

            // Clone questions
            foreach ($quiz->questions as $question) {
                $newQuestion = $question->replicate();
                $newQuestion->quiz_id = $newQuiz->id;
                $newQuestion->save();
            }

            DB::commit();

            return redirect()->route('quizzes.show', $newQuiz)
                ->with('success', 'تم نسخ الاختبار بنجاح. يمكنك الآن تعديله.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Quiz duplication failed', [
                'error' => $e->getMessage(),
                'original_quiz_id' => $quiz->id
            ]);

            return redirect()->route('quizzes.show', $quiz)
                ->with('error', 'فشل نسخ الاختبار.');
        }
    }

    /**
     * Take quiz (public access with PIN or authenticated)
     */
    public function take(Quiz $quiz)
    {
        if (!$quiz->is_active) {
            abort(404, 'هذا الاختبار غير متاح حالياً.');
        }

        // For guests: check if they have name in session OR if they want to use a different name
        if (!Auth::check() && !session('guest_name')) {
            return view('quiz.guest-info', compact('quiz'));
        }

        $quiz->load('questions');

        if ($quiz->questions->isEmpty()) {
            return redirect()->route('quiz.enter-pin')
                ->with('error', 'لا يحتوي هذا الاختبار على أسئلة بعد.');
        }

        // Apply question shuffling if enabled
        $questions = $quiz->questions;
        if ($quiz->shuffle_questions) {
            $questions = $questions->shuffle();
        }

        // Apply answer shuffling if enabled
        if ($quiz->shuffle_answers) {
            $questions->transform(function ($question) {
                $options = collect($question->options);
                $shuffled = $options->shuffle();
                $question->shuffled_options = $shuffled->values()->all();

                // Map correct answer to new position
                $correctIndex = array_search($question->correct_answer, $question->shuffled_options);
                $question->shuffled_correct_index = $correctIndex;

                return $question;
            });
        }

        return view('quizzes.take', compact('quiz', 'questions'));
    }

    /**
     * Handle guest name submission
     */
    public function guestStart(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'guest_name' => 'required|string|max:255',
            'school_class' => 'nullable|string|max:255'
        ]);

        session([
            'guest_name' => $validated['guest_name'],
            'school_class' => $validated['school_class'] ?? null
        ]);

        return redirect()->route('quiz.take', $quiz);
    }

    /**
     * Submit quiz answers
     */
    public function submit(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string'
        ]);

        DB::beginTransaction();

        try {
            // Initialize root scores
            $rootScores = ['jawhar' => 0, 'zihn' => 0, 'waslat' => 0, 'roaya' => 0];
            $rootCounts = ['jawhar' => 0, 'zihn' => 0, 'waslat' => 0, 'roaya' => 0];
            $totalScore = 0;

            // Calculate attempt number for registered users
            $attemptNumber = 1;
            if (Auth::check()) {
                $lastAttempt = Result::where('quiz_id', $quiz->id)
                    ->where('user_id', Auth::id())
                    ->max('attempt_number');
                $attemptNumber = $lastAttempt ? $lastAttempt + 1 : 1;

                // Check attempt limit
                if ($quiz->max_attempts && $attemptNumber > $quiz->max_attempts) {
                    return back()->with('error', 'لقد تجاوزت العدد المسموح من المحاولات لهذا الاختبار.');
                }

                // Mark previous attempts as not latest
                Result::where('quiz_id', $quiz->id)
                    ->where('user_id', Auth::id())
                    ->update(['is_latest_attempt' => false]);
            }

            // Create result
            $result = Result::create([
                'quiz_id' => $quiz->id,
                'user_id' => Auth::id(),
                'guest_token' => !Auth::check() ? Str::random(32) : null,
                'guest_name' => !Auth::check() ? session('guest_name') : null,
                'attempt_number' => $attemptNumber,
                'is_latest_attempt' => true,
                'scores' => $rootScores,
                'total_score' => 0,
                'expires_at' => !Auth::check() ? now()->addDays(7) : null,
            ]);

            // Process each answer
            foreach ($validated['answers'] as $questionId => $selectedAnswer) {
                $question = Question::findOrFail($questionId);
                $isCorrect = $question->correct_answer === $selectedAnswer;

                // Save individual answer
                Answer::create([
                    'question_id' => $question->id,
                    'result_id' => $result->id,
                    'selected_answer' => $selectedAnswer,
                    'is_correct' => $isCorrect,
                ]);

                // Update root scores
                if ($isCorrect) {
                    $rootScores[$question->root_type]++;
                    $totalScore++;
                }
                $rootCounts[$question->root_type]++;
            }

            // Calculate percentages
            foreach ($rootScores as $root => $score) {
                if ($rootCounts[$root] > 0) {
                    $rootScores[$root] = round(($score / $rootCounts[$root]) * 100);
                }
            }

            // Update result with final scores
            $result->update([
                'scores' => $rootScores,
                'total_score' => $quiz->questions->count() > 0
                    ? round(($totalScore / $quiz->questions->count()) * 100)
                    : 0
            ]);

            // Mark quiz as having submissions
            if (!$quiz->has_submissions) {
                $quiz->update(['has_submissions' => true]);
            }

            DB::commit();

            // Clear guest session data after submission
            if (!Auth::check()) {
                session(['guest_token' => $result->guest_token]);
            }

            Log::info('Quiz submitted successfully', [
                'quiz_id' => $quiz->id,
                'result_id' => $result->id,
                'total_score' => $result->total_score,
                'user_id' => Auth::id(),
                'guest_name' => $result->guest_name
            ]);

            // Redirect based on user authentication status
            if (Auth::check()) {
                // Authenticated users go to regular results page
                return redirect()->route('results.show', $result)
                    ->with('success', 'تم إرسال إجاباتك بنجاح!');
            } else {
                // Guests go to token-based results page
                return redirect()->route('quiz.guest-result', ['result' => $result->guest_token])->with('success', 'تم إرسال إجاباتك بنجاح!');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Quiz submission failed', [
                'error' => $e->getMessage(),
                'quiz_id' => $quiz->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('quiz.take', $quiz)
                ->with('error', 'نحتاج إلى إعادة تجربة حفظ إجاباتك. الرجاء المحاولة مرة أخرى.');
        }
    }

    /**
     * Generate educational text using AI (AJAX endpoint)
     */
    public function generateText(Request $request, ?Quiz $quiz = null)
    {
        $this->authorizeQuizManagement();

        // Handle both wizard and standalone text generation
        if ($quiz) {
            $this->authorizeQuizOwnership($quiz);

            $validator = Validator::make($request->all(), [
                'topic' => 'required|string|max:255',
                'passage_topic' => 'required|string|max:255',
                'text_type' => 'required|in:story,article,dialogue,informational,description',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            try {
                // Get subject name for AI context
                $subject = Subject::find($quiz->subject_id);
                $subjectName = $subject ? $subject->name : 'عام';

                Log::info('Generating educational text for quiz', [
                    'quiz_id' => $quiz->id,
                    'subject' => $subjectName,
                    'topic' => $request->passage_topic,
                    'text_type' => $request->text_type
                ]);

                // Call Claude AI service
                $generatedText = $this->claudeService->generateEducationalText(
                    $subjectName,
                    $quiz->grade_level,
                    $request->passage_topic,
                    $request->text_type,
                    'medium'
                );

                if (!$generatedText) {
                    throw new \Exception('AI service returned empty text');
                }

                // Save text to quiz settings
                $settings = $quiz->settings ?? [];
                $settings['step'] = '3a';
                $settings['main_topic'] = $request->topic;
                $settings['passage_topic'] = $request->passage_topic;
                $settings['text_type'] = $request->text_type;
                $settings['generated_text'] = $generatedText;

                $quiz->update(['settings' => $settings]);

                return response()->json([
                    'success' => true,
                    'text' => $generatedText,
                    'message' => 'تم إنشاء النص بنجاح'
                ]);

            } catch (\Exception $e) {
                Log::error('Text generation failed', [
                    'error' => $e->getMessage(),
                    'quiz_id' => $quiz->id,
                    'user_id' => Auth::id(),
                    'request_data' => $request->all()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء إنشاء النص: ' . $e->getMessage()
                ], 500);
            }
        } else {
            //  subscription check here:
            if (!Auth::user()->canUseAI()) {
                return response()->json([
                    'success' => false,
                    'message' => 'يتطلب توليد النص اشتراك نشط',
                    'upgrade_required' => true
                ], 403);
            }
            // Standalone text generation (original method)
            $validated = $request->validate([
                'subject_id' => 'required|exists:subjects,id',
                'grade_level' => 'required|integer|min:1|max:9',
                'topic' => 'required|string|max:255',
                'text_type' => 'required|in:story,article,dialogue,description',
                'length' => 'required|in:short,medium,long'
            ]);

            try {
                // Get subject name
                $subject = Subject::find($validated['subject_id']);
                $subjectName = $subject ? $subject->name : 'عام';

                $text = $this->claudeService->generateEducationalText(
                    $subjectName,
                    $validated['grade_level'],
                    $validated['topic'],
                    $validated['text_type'],
                    $validated['length']
                );

                // Log AI usage
                $quota = \App\Models\MonthlyQuota::getOrCreateCurrent(Auth::id());
                $quota->incrementAiTextRequests();

                return response()->json([
                    'success' => true,
                    'text' => $text
                ]);

            } catch (\Exception $e) {
                Log::error('Text generation failed', [
                    'error' => $e->getMessage(),
                    'params' => $validated,
                    'user_id' => Auth::id()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'لم نتمكن من توليد النص حالياً: ' . $e->getMessage()
                ], 422);
            }
        }
    }

    /**
     * Parse AI response and save questions to database
     * 
     * @param Quiz $quiz
     * @param array $aiResponse
     * @return int Number of questions created
     */
    private function parseAndSaveQuestions(Quiz $quiz, array $aiResponse): int
    {
        if (!isset($aiResponse['questions']) || !is_array($aiResponse['questions'])) {
            throw new \Exception('Invalid AI response format: missing questions array');
        }

        $passage = $aiResponse['passage'] ?? null;
        $passageTitle = $aiResponse['passage_title'] ?? null;
        $questionsCreated = 0;

        foreach ($aiResponse['questions'] as $index => $questionData) {
            if (!isset($questionData['question']) || !isset($questionData['options'])) {
                Log::warning('Skipping invalid question data', [
                    'index' => $index,
                    'question_data' => $questionData
                ]);
                continue;
            }

            try {
                $options = is_array($questionData['options']) ?
                    $questionData['options'] :
                    json_decode($questionData['options'], true);

                if (!is_array($options) || empty($options)) {
                    Log::warning('Invalid options for question', [
                        'index' => $index,
                        'options' => $questionData['options']
                    ]);
                    continue;
                }

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

                $questionsCreated++;

            } catch (\Exception $e) {
                Log::error('Failed to create question', [
                    'error' => $e->getMessage(),
                    'index' => $index,
                    'question_data' => $questionData
                ]);
                continue;
            }
        }

        Log::info('Questions parsing completed', [
            'quiz_id' => $quiz->id,
            'total_questions' => count($aiResponse['questions']),
            'questions_created' => $questionsCreated,
            'has_passage' => !empty($passage)
        ]);

        return $questionsCreated;
    }
    /**
     * Toggle quiz active status
     */
    public function toggleStatus(Quiz $quiz)
    {
        $this->authorizeQuizManagement();

        if (!Auth::user()->is_admin && (int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        $quiz->update(['is_active' => !$quiz->is_active]);

        $message = $quiz->is_active ? 'تم تفعيل الاختبار بنجاح.' : 'تم إلغاء تفعيل الاختبار بنجاح.';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Show quiz results
     */
    public function results(Quiz $quiz)
    {
        $this->authorizeQuizManagement();

        if (!Auth::user()->is_admin && (int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        return redirect()->route('results.quiz', $quiz);
    }
    public function saveManualText(Request $request, Quiz $quiz)
    {
        Log::info('saveManualText called', [
            'quiz_id' => $quiz->id,
            'request_data' => $request->all()
        ]);

        $validated = $request->validate([
            'educational_text' => 'required|string|min:50',
            'topic' => 'required|string',
        ]);

        try {
            $quiz->update([
                'passage_data' => [
                    'passage' => $validated['educational_text'],
                    'passage_title' => $validated['topic'],
                    'saved_at' => now()->toISOString(),
                    'text_source' => 'manual'
                ]
            ]);

            Log::info('Manual text saved successfully', [
                'quiz_id' => $quiz->id,
                'text_length' => strlen($validated['educational_text'])
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ النص بنجاح'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to save manual text', [
                'quiz_id' => $quiz->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'فشل حفظ النص'
            ], 500);
        }
    }
}