<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\QuizController as AdminQuizController;
use App\Http\Controllers\Admin\AiManagementController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\SubjectController;


/*
|--------------------------------------------------------------------------
| Public Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', [WelcomeController::class, 'index'])->name('home');

// Static Pages
Route::get('/about', fn() => view('about'))->name('about');
Route::get('/juzoor-model', fn() => view('juzoor-model'))->name('juzoor.model');
Route::get('/juzoor-model/growth', fn() => view('juzoor-growth'))->name('juzoor.growth');
Route::get('/question-guide', fn() => view('question-guide'))->name('question.guide');
Route::get('/for-teachers', fn() => view('for-teachers'))->name('for.teachers');
Route::get('/for-students', fn() => view('for-students'))->name('for.students');

// Contact Routes
Route::controller(ContactController::class)->prefix('contact')->name('contact.')->group(function () {
    Route::get('/', 'show')->name('show');
    Route::post('/', 'submit')->name('submit');
});

// Language Switcher
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'he', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Quiz Taking (Public Access)
Route::prefix('quiz')->name('quiz.')->group(function () {
    // PIN-based access (for guest users)
    Route::post('/enter-pin', [WelcomeController::class, 'enterPin'])->name('enter-pin');
    Route::get('/pin/{quiz:pin}/take', [QuizController::class, 'take'])->name('take-by-pin');
    Route::post('/pin/{quiz:pin}/submit', [QuizController::class, 'submit'])->name('submit-by-pin');

    // Guest results
    Route::get('/result/{result:token}', [ResultController::class, 'guestShow'])->name('guest-result');
});

// Quiz Taking by ID (separate from prefix to avoid conflicts)
Route::get('/quiz/{quiz}/take', [QuizController::class, 'take'])->name('quiz.take');
Route::post('/quiz/{quiz}/submit', [QuizController::class, 'submit'])->name('quiz.submit');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Simple Logout Route (No Middleware)
|--------------------------------------------------------------------------
*/
Route::get('/logout-now', function () {
    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout.simple');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (All Logged In Users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // Teacher Pending Approval Page
    Route::get('/teacher/pending-approval', function () {
        if (Auth::user()->user_type !== 'teacher' || Auth::user()->is_approved) {
            return redirect()->route('dashboard');
        }
        return view('auth.teacher.pending-approval');
    })->name('teacher.pending-approval');

    // Stop Impersonation (MUST be outside admin middleware)
    Route::get('/admin/stop-impersonation', [AdminUserController::class, 'stopImpersonation'])
        ->name('admin.stop-impersonation');

    /*
    |--------------------------------------------------------------------------
    | Profile Management
    |--------------------------------------------------------------------------
    */
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'dashboard')->name('dashboard');
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

    // Password Update
    Route::put('/profile/password', [PasswordController::class, 'update'])->name('password.update');

    /*
    |--------------------------------------------------------------------------
    | Quiz Management (Teachers and Admins)
    |--------------------------------------------------------------------------
    */
    Route::resource('quizzes', QuizController::class);
    Route::post('/quizzes/{quiz}/duplicate', [QuizController::class, 'duplicate'])->name('quizzes.duplicate');

    // AI Text Generation (outside of question management)
    Route::post('/quizzes/generate-text', [QuizController::class, 'generateText'])->name('quizzes.generate-text');

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

        // AI Suggestions (Fixed route)
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
    Route::resource('subjects', App\Http\Controllers\Admin\SubjectController::class);
    Route::post('subjects/{subject}/toggle-status', [App\Http\Controllers\Admin\SubjectController::class, 'toggleStatus'])->name('subjects.toggle-status');
    // Quiz Management
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

    // AI Management
    Route::prefix('ai')->name('ai.')->controller(AiManagementController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/generate', 'generate')->name('generate');
        Route::post('/quiz/{quiz}/report', 'generateReport')->name('generateReport');
    });
});