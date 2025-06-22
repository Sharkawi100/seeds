<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\AiManagementController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\QuizController as AdminQuizController;
use App\Http\Controllers\Admin\LogAnalyzerController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;
use App\Models\Quiz;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Public Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/

// Landing Pages
Route::get('/', [WelcomeController::class, 'index'])->name('home');
Route::get('/about', fn() => view('about'))->name('about');
Route::get('/juzoor-model', fn() => view('juzoor-model'))->name('juzoor.model');
Route::get('/juzoor-model/growth', fn() => view('juzoor-growth'))->name('juzoor.growth');
Route::get('/question-guide', fn() => view('question-guide'))->name('question.guide');
Route::get('/for-teachers', fn() => view('for-teachers'))->name('for.teachers');
Route::get('/for-students', fn() => view('for-students'))->name('for.students');

// Language Switcher
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'he', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Contact Form
Route::prefix('contact')->name('contact.')->group(function () {
    Route::get('/', function () {
        if (view()->exists('contact.show')) {
            return view('contact.show');
        }
        return view('welcome');
    })->name('show');
    Route::post('/', function () {
        request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string'
        ]);
        session()->flash('success', 'تم إرسال رسالتك بنجاح');
        return redirect()->route('contact.show');
    })->name('submit');
});

// Public Quiz Access (unified approach)
Route::prefix('quiz')->name('quiz.')->group(function () {
    Route::post('/enter-pin', [WelcomeController::class, 'enterPin'])->name('enter-pin');

    // Demo quiz
    Route::get('/demo', function () {
        $demoQuiz = \App\Models\Quiz::where('is_demo', 1)
            ->where('is_active', 1)
            ->first();

        if ($demoQuiz) {
            return redirect()->route('quiz.take', $demoQuiz);
        }

        return redirect()->route('home')->with('error', 'الاختبار التجريبي غير متوفر حالياً.');
    })->name('demo');

    // Guest results
    Route::get('/result/{result:guest_token}', [ResultController::class, 'guestShow'])->name('guest-result');
});

// Unified Quiz Taking (works for both PIN and direct access)
Route::get('/quiz/{quiz}/take', [QuizController::class, 'take'])->name('quiz.take');
Route::post('/quiz/{quiz}/submit', [QuizController::class, 'submit'])->name('quiz.submit');
Route::post('/quiz/{quiz}/guest-start', [QuizController::class, 'guestStart'])->name('quiz.guest-start');

/*
|--------------------------------------------------------------------------
| Authentication Routes (Guest Only)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Role selection pages
    Route::get('/register', fn() => view('auth.role-selection', ['action' => 'register']))->name('register');
    Route::get('/login', fn() => view('auth.role-selection', ['action' => 'login']))->name('login');

    // Teacher Authentication
    Route::prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/login', fn() => view('auth.teacher.login'))->name('login');
        Route::get('/register', fn() => view('auth.teacher.register'))->name('register');
        Route::get('/pending-approval', fn() => view('auth.teacher.pending-approval'))->name('pending-approval');
    });

    // Student Authentication
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/login', [App\Http\Controllers\Auth\Student\StudentLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [App\Http\Controllers\Auth\Student\StudentLoginController::class, 'login']);
        Route::post('/pin-login', [App\Http\Controllers\Auth\Student\StudentLoginController::class, 'pinLogin'])->name('pin-login');
        Route::get('/register', fn() => view('auth.student.register'))->name('register');
    });
});

/*
|--------------------------------------------------------------------------
| Authentication Routes (Include Laravel Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Authenticated Routes (All Logged-in Users)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');
    // Perspective Switching for Admins
    Route::middleware('admin')->group(function () {
        Route::post('/switch-to-teacher', [ProfileController::class, 'switchToTeacher'])->name('switch.teacher');
        Route::post('/switch-to-admin', [ProfileController::class, 'switchToAdmin'])->name('switch.admin');
    });
    // Profile Management
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'profileDashboard')->name('dashboard');
        Route::get('/edit', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
        Route::post('/avatar', 'updateAvatar')->name('avatar.update');
        Route::get('/completion', 'completion')->name('completion');
        Route::get('/sessions', 'sessions')->name('sessions');
        Route::post('/logout-other-devices', 'logoutOtherDevices')->name('logout-other-devices');
        Route::patch('/preferences', 'updatePreferences')->name('preferences');
        Route::patch('/privacy', 'updatePrivacy')->name('privacy');
    });
    Route::get('/reports', [App\Http\Controllers\ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports', [App\Http\Controllers\ReportsController::class, 'index'])->name('reports.index');

    // Password Update
    Route::put('/profile/password', [PasswordController::class, 'update'])->name('password.update');

    /*
    |--------------------------------------------------------------------------
    | Quiz Management (Teachers and Admins Only)
    |--------------------------------------------------------------------------
    */

    // Basic Quiz CRUD
    Route::resource('quizzes', QuizController::class);

    // Quiz Creation Wizard Routes
    Route::prefix('quizzes')->name('quizzes.')->group(function () {
        Route::post('/create-step-1', [QuizController::class, 'createStep1'])->name('create-step-1');
        Route::post('/{quiz}/update-method', [QuizController::class, 'updateMethod'])->name('update-method');
        Route::post('/{quiz}/generate-text', [QuizController::class, 'generateText'])->name('generate-text');
        Route::post('/{quiz}/generate-questions', [QuizController::class, 'generateQuestions'])->name('generate-questions');
        Route::post('/{quiz}/finalize', [QuizController::class, 'finalizeQuiz'])->name('finalize');
        Route::post('/{quiz}/duplicate', [QuizController::class, 'duplicate'])->name('duplicate');
        Route::patch('/{quiz}/toggle-status', [QuizController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/{quiz}/results', [QuizController::class, 'results'])->name('results');

    });

    // AI Text Generation (Global)
    Route::post('/generate-text', [QuizController::class, 'generateText'])->name('quizzes.generate-text');

    /*
    |--------------------------------------------------------------------------
    | Question Management Routes
    |--------------------------------------------------------------------------
    */

    Route::prefix('quizzes/{quiz}/questions')->name('quizzes.questions.')->group(function () {
        // Basic CRUD operations
        Route::get('/', [QuestionController::class, 'index'])->name('index');
        Route::get('/bulk-edit', [QuestionController::class, 'bulkEdit'])->name('bulk-edit');
        Route::get('/create', [QuestionController::class, 'create'])->name('create');
        Route::post('/', [QuestionController::class, 'store'])->name('store');
        Route::put('/bulk-update', [QuestionController::class, 'bulkUpdate'])->name('bulk-update');
        Route::delete('/bulk-delete', [QuestionController::class, 'bulkDelete'])->name('bulk-delete');

        // Individual question operations
        Route::get('/{question}/edit', [QuestionController::class, 'edit'])->name('edit');
        Route::put('/{question}', [QuestionController::class, 'update'])->name('update');
        Route::delete('/{question}', [QuestionController::class, 'destroy'])->name('destroy');
        Route::post('/{question}/update-text', [QuestionController::class, 'updateText'])->name('update-text');
        Route::post('/{question}/clone', [QuestionController::class, 'clone'])->name('clone');

        // AI Suggestions
        Route::post('/{question}/suggestions', [QuestionController::class, 'generateSuggestions'])->name('suggestions');
    });

    /*
    |--------------------------------------------------------------------------
    | Results Management
    |--------------------------------------------------------------------------
    */

    Route::prefix('results')->name('results.')->controller(ResultController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/quiz/{quiz}', 'quizResults')->name('quiz');
        Route::get('/{result}', 'show')->name('show');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Admin Only)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {

    // Admin Dashboard & Reports
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/reports', 'reports')->name('reports');
        Route::get('/settings', 'settings')->name('settings');
    });

    // Subject Management
    Route::resource('subjects', SubjectController::class);
    Route::post('subjects/{subject}/toggle-status', [SubjectController::class, 'toggleStatus'])->name('subjects.toggle-status');

    // Quiz Management (Admin)
    Route::resource('quizzes', AdminQuizController::class);
    Route::post('quizzes/{quiz}/toggle-status', [AdminQuizController::class, 'toggleStatus'])->name('quizzes.toggle-status');
    Route::get('quizzes-export', [AdminQuizController::class, 'export'])->name('quizzes.export');

    // User Management
    Route::resource('users', AdminUserController::class);
    Route::post('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('users/{user}/update-role', [AdminUserController::class, 'updateRole'])->name('users.update-role');
    Route::post('users/{user}/disconnect-social', [AdminUserController::class, 'disconnectSocial'])->name('users.disconnect-social');
    Route::get('users/{user}/impersonate', [AdminUserController::class, 'impersonate'])->name('users.impersonate');
    Route::get('users-export', [AdminUserController::class, 'export'])->name('users.export');

    // Subscription Plans Management
    Route::resource('subscription-plans', App\Http\Controllers\Admin\SubscriptionPlanController::class);
    Route::patch('subscription-plans/{subscriptionPlan}/toggle', [App\Http\Controllers\Admin\SubscriptionPlanController::class, 'toggle'])->name('subscription-plans.toggle');
    Route::get('subscription-plans-users', [App\Http\Controllers\Admin\SubscriptionPlanController::class, 'users'])->name('subscription-plans.users');
    Route::get('users/{user}/manage-subscription', [App\Http\Controllers\Admin\SubscriptionPlanController::class, 'manageUserSubscription'])->name('users.manage-subscription');
    Route::put('users/{user}/update-subscription', [App\Http\Controllers\Admin\SubscriptionPlanController::class, 'updateUserSubscription'])->name('subscription-plans.update-user');


    // AI Management
    Route::prefix('ai')->name('ai.')->controller(AiManagementController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/generate', 'generate')->name('generate');
        Route::post('/quiz/{quiz}/report', 'generateReport')->name('generateReport');
    });
    // In routes/web.php (admin section)
    Route::get('/admin/quiz-cleanup-stats', function () {
        $incompleteQuizzes = Quiz::whereDoesntHave('questions')->count();
        $oldIncompleteQuizzes = Quiz::where('created_at', '<', now()->subHours(6))
            ->whereDoesntHave('questions')->count();

        return response()->json([
            'incomplete_quizzes_total' => $incompleteQuizzes,
            'ready_for_cleanup' => $oldIncompleteQuizzes
        ]);
    });

    // Log Analyzer
    Route::prefix('logs')->name('logs.')->controller(LogAnalyzerController::class)->group(function () {
        Route::get('/analyzer', 'index')->name('analyzer');
        Route::post('/clear', 'clearLogs')->name('clear');
        Route::get('/download', 'downloadLogs')->name('download');
    });
});

/*
|--------------------------------------------------------------------------
| Special Routes
|--------------------------------------------------------------------------
*/

// Impersonation Stop (Outside admin middleware)  
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/stop-impersonation', [App\Http\Controllers\Admin\UserController::class, 'stopImpersonation'])
        ->name('admin.stop-impersonation');
    Route::post('/quizzes/{quiz}/save-manual-text', [QuizController::class, 'saveManualText'])->name('quizzes.save-manual-text');

});

/*
|--------------------------------------------------------------------------
| API Routes (If needed for AJAX)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->prefix('api')->name('api.')->group(function () {
    // Add API routes here if needed
    Route::get('/user', fn() => request()->user());
});

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    abort(404);
});

// Subscription routes
Route::middleware(['auth'])->group(function () {
    Route::get('/subscription/upgrade', [App\Http\Controllers\SubscriptionController::class, 'upgrade'])->name('subscription.upgrade');
    Route::post('/subscription/checkout', [App\Http\Controllers\SubscriptionController::class, 'createCheckout'])->name('subscription.checkout');
    Route::get('/subscription/success', [App\Http\Controllers\SubscriptionController::class, 'success'])->name('subscription.success');
    Route::get('/subscription/manage', [App\Http\Controllers\SubscriptionController::class, 'manage'])->name('subscription.manage');
});

// Webhook (no auth needed)
Route::post('/webhooks/lemonsqueezy', [App\Http\Controllers\SubscriptionController::class, 'webhook'])->name('subscription.webhook');

// AI features (require subscription)
Route::middleware(['auth', 'subscription'])->group(function () {
    Route::post('/quiz/generate-text', [App\Http\Controllers\QuizController::class, 'generateText'])->name('quiz.generate-text');
});
// Add this to routes/web.php temporarily
Route::get('/debug-quiz/{quiz}', function (Quiz $quiz) {
    return response()->json([
        'quiz_id' => $quiz->id,
        'title' => $quiz->title,
        'passage_data' => $quiz->passage_data,
        'settings' => $quiz->settings,
        'questions_count' => $quiz->questions->count()
    ]);
});