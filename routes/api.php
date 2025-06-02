<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\Admin\AiManagementController;
use App\Services\ClaudeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API Routes (No Authentication Required)
Route::prefix('v1')->group(function () {
    // Health check endpoint
    Route::get('/health', function () {
        return response()->json([
            'status' => 'healthy',
            'timestamp' => now()->toIso8601String(),
            'version' => '1.0.0'
        ]);
    });

    // Test Claude connection (public for debugging)
    Route::get('/test-claude', function () {
        try {
            $service = app(ClaudeService::class);
            return response()->json($service->testConnection());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });
});

// Authenticated API Routes
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    // User endpoints
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::put('/user/profile', function (Request $request) {
        $user = $request->user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);
        return response()->json($user);
    });

    // Quiz endpoints
    Route::prefix('quizzes')->group(function () {
        // List user's quizzes with pagination
        Route::get('/', [QuizController::class, 'apiIndex']);

        // Get quiz details
        Route::get('/{quiz}', [QuizController::class, 'apiShow']);

        // Create new quiz
        Route::post('/', [QuizController::class, 'apiStore']);

        // Update quiz
        Route::put('/{quiz}', [QuizController::class, 'apiUpdate']);

        // Delete quiz
        Route::delete('/{quiz}', [QuizController::class, 'apiDestroy']);

        // Quiz statistics
        Route::get('/{quiz}/stats', [QuizController::class, 'apiStats']);
    });

    // Question endpoints
    Route::prefix('quizzes/{quiz}/questions')->group(function () {
        // List questions for a quiz
        Route::get('/', [QuestionController::class, 'apiIndex']);

        // Add question to quiz
        Route::post('/', [QuestionController::class, 'apiStore']);

        // Update question
        Route::put('/{question}', [QuestionController::class, 'apiUpdate']);

        // Update question text only (for inline editing)
        Route::patch('/{question}/text', [QuestionController::class, 'apiUpdateText']);

        // Update question passage
        Route::patch('/{question}/passage', [QuestionController::class, 'apiUpdatePassage']);

        // Delete question
        Route::delete('/{question}', [QuestionController::class, 'apiDestroy']);

        // Bulk operations
        Route::post('/bulk-delete', [QuestionController::class, 'apiBulkDelete']);
        Route::post('/reorder', [QuestionController::class, 'apiReorder']);
    });

    // AI Generation endpoints
    Route::prefix('ai')->group(function () {
        // Generate passage only
        Route::post('/generate-passage', [AiManagementController::class, 'apiGeneratePassage']);

        // Generate questions for existing passage
        Route::post('/generate-questions', [AiManagementController::class, 'apiGenerateQuestions']);

        // Generate complete quiz (passage + questions)
        Route::post('/generate-quiz', [AiManagementController::class, 'apiGenerateQuiz']);

        // Generate result report
        Route::post('/generate-report', [AiManagementController::class, 'apiGenerateReport']);

        // Regenerate specific question
        Route::post('/regenerate-question', [AiManagementController::class, 'apiRegenerateQuestion']);
    });

    // Results endpoints
    Route::prefix('results')->group(function () {
        // Get user's results
        Route::get('/', [ResultController::class, 'apiIndex']);

        // Get specific result
        Route::get('/{result}', [ResultController::class, 'apiShow']);

        // Submit quiz answers
        Route::post('/submit', [ResultController::class, 'apiSubmit']);

        // Generate PDF report
        Route::get('/{result}/pdf', [ResultController::class, 'apiGeneratePdf']);
    });

    // Analytics endpoints
    Route::prefix('analytics')->group(function () {
        // User progress overview
        Route::get('/progress', function (Request $request) {
            $user = $request->user();

            return response()->json([
                'total_quizzes_taken' => $user->results()->count(),
                'average_score' => round($user->results()->avg('total_score') ?? 0, 2),
                'root_performance' => [
                    'jawhar' => round($user->results()->avg('scores->jawhar') ?? 0, 2),
                    'zihn' => round($user->results()->avg('scores->zihn') ?? 0, 2),
                    'waslat' => round($user->results()->avg('scores->waslat') ?? 0, 2),
                    'roaya' => round($user->results()->avg('scores->roaya') ?? 0, 2),
                ],
                'recent_activity' => $user->results()
                    ->with('quiz:id,title')
                    ->latest()
                    ->take(5)
                    ->get(['id', 'quiz_id', 'total_score', 'created_at'])
            ]);
        });

        // Quiz performance analytics
        Route::get('/quiz/{quiz}', function (Request $request, $quizId) {
            $quiz = \App\Models\Quiz::where('user_id', $request->user()->id)
                ->findOrFail($quizId);

            return response()->json([
                'quiz_id' => $quiz->id,
                'title' => $quiz->title,
                'total_attempts' => $quiz->results()->count(),
                'average_score' => round($quiz->results()->avg('total_score') ?? 0, 2),
                'completion_rate' => $quiz->results()->count() > 0
                    ? round(($quiz->results()->where('total_score', '>=', 60)->count() / $quiz->results()->count()) * 100, 2)
                    : 0,
                'question_analytics' => $quiz->questions->map(function ($question) {
                    $answers = $question->answers;
                    $totalAnswers = $answers->count();
                    $correctAnswers = $answers->where('is_correct', true)->count();

                    return [
                        'question_id' => $question->id,
                        'root_type' => $question->root_type,
                        'depth_level' => $question->depth_level,
                        'success_rate' => $totalAnswers > 0
                            ? round(($correctAnswers / $totalAnswers) * 100, 2)
                            : 0,
                        'total_attempts' => $totalAnswers
                    ];
                })
            ]);
        });
    });

    // Admin-only endpoints
    Route::middleware('admin')->prefix('admin')->group(function () {
        // Dashboard stats
        Route::get('/stats', function () {
            return response()->json([
                'total_users' => \App\Models\User::count(),
                'total_quizzes' => \App\Models\Quiz::count(),
                'total_questions' => \App\Models\Question::count(),
                'total_results' => \App\Models\Result::count(),
                'recent_users' => \App\Models\User::latest()->take(5)->get(['id', 'name', 'email', 'created_at']),
                'popular_quizzes' => \App\Models\Quiz::withCount('results')
                    ->orderBy('results_count', 'desc')
                    ->take(5)
                    ->get(['id', 'title', 'subject', 'grade_level', 'results_count'])
            ]);
        });

        // User management
        Route::get('/users', function (Request $request) {
            return \App\Models\User::when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
                ->paginate($request->per_page ?? 20);
        });

        Route::put('/users/{user}/toggle-admin', function (\App\Models\User $user) {
            $user->update(['is_admin' => !$user->is_admin]);
            return response()->json(['success' => true, 'is_admin' => $user->is_admin]);
        });

        // System health
        Route::get('/system/health', function () {
            return response()->json([
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'database' => DB::connection()->getPdo() ? 'connected' : 'disconnected',
                'cache' => Cache::has('health_check') ? 'working' : 'unknown',
                'claude_api' => app(ClaudeService::class)->testConnection()['success'] ? 'connected' : 'disconnected',
                'disk_space' => [
                    'free' => disk_free_space('/'),
                    'total' => disk_total_space('/'),
                    'percentage' => round((disk_free_space('/') / disk_total_space('/')) * 100, 2)
                ]
            ]);
        });
    });
});

// Guest Quiz Taking (Public API)
Route::prefix('v1/public')->group(function () {
    // Get quiz for taking (limited info)
    Route::get('/quiz/{quiz}/take', function (\App\Models\Quiz $quiz) {
        return response()->json([
            'id' => $quiz->id,
            'title' => $quiz->title,
            'subject' => $quiz->subject,
            'grade_level' => $quiz->grade_level,
            'questions_count' => $quiz->questions()->count(),
            'has_passage' => $quiz->questions()->whereNotNull('passage')->exists()
        ]);
    });

    // Get quiz questions for taking
    Route::get('/quiz/{quiz}/questions', function (\App\Models\Quiz $quiz) {
        $questions = $quiz->questions()->get(['id', 'question', 'options', 'root_type', 'depth_level', 'passage', 'passage_title']);

        // Remove correct answers for security
        $questions = $questions->map(function ($question) {
            return [
                'id' => $question->id,
                'question' => $question->question,
                'options' => $question->options,
                'root_type' => $question->root_type,
                'depth_level' => $question->depth_level,
                'passage' => $question->passage,
                'passage_title' => $question->passage_title,
            ];
        });

        return response()->json($questions);
    });

    // Submit guest quiz
    Route::post('/quiz/{quiz}/submit', function (Request $request, \App\Models\Quiz $quiz) {
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string',
            'guest_name' => 'nullable|string|max:255'
        ]);

        // Process answers and calculate scores
        $rootScores = ['jawhar' => 0, 'zihn' => 0, 'waslat' => 0, 'roaya' => 0];
        $rootCounts = ['jawhar' => 0, 'zihn' => 0, 'waslat' => 0, 'roaya' => 0];
        $totalScore = 0;
        $correctCount = 0;

        foreach ($validated['answers'] as $questionId => $selectedAnswer) {
            $question = $quiz->questions->find($questionId);
            if (!$question)
                continue;

            $isCorrect = $question->correct_answer === $selectedAnswer;
            if ($isCorrect) {
                $correctCount++;
                $rootScores[$question->root_type]++;
            }
            $rootCounts[$question->root_type]++;
        }

        // Calculate percentages
        foreach ($rootScores as $root => $score) {
            if ($rootCounts[$root] > 0) {
                $rootScores[$root] = round(($score / $rootCounts[$root]) * 100);
            }
        }

        $totalScore = $quiz->questions->count() > 0
            ? round(($correctCount / $quiz->questions->count()) * 100)
            : 0;

        // Create guest token
        $guestToken = Str::random(32);

        // Save result
        $result = \App\Models\Result::create([
            'quiz_id' => $quiz->id,
            'user_id' => null,
            'guest_token' => $guestToken,
            'guest_name' => $validated['guest_name'] ?? 'ضيف',
            'scores' => $rootScores,
            'total_score' => $totalScore,
            'expires_at' => now()->addDays(7)
        ]);

        return response()->json([
            'success' => true,
            'result_id' => $result->id,
            'guest_token' => $guestToken,
            'total_score' => $totalScore,
            'scores' => $rootScores,
            'redirect' => route('results.show', $result)
        ]);
    });
});

// Fallback route
Route::fallback(function () {
    return response()->json([
        'message' => 'Endpoint not found. Please check the API documentation.'
    ], 404);
});