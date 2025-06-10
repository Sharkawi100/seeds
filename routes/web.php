<?php
// File: routes/web.php

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
    Route::get('/', function () {
        return redirect()->route('home');
    })->name('index');
    Route::post('/enter-pin', [WelcomeController::class, 'enterPin'])->name('enter-pin');
    Route::get('/demo', [WelcomeController::class, 'demo'])->name('demo');
    Route::get('/{quiz}/take', [QuizController::class, 'take'])->name('take');
    Route::post('/{quiz}/submit', [QuizController::class, 'submit'])->name('submit');
    Route::post('/{quiz}/guest-start', [QuizController::class, 'guestStart'])->name('guest-start');
});

// Results Viewing (Guest with Token or Authenticated)
Route::get('/results/{result}', [ResultController::class, 'show'])->name('results.show');

/*
|--------------------------------------------------------------------------
| Guest Only Routes (Redirect to Dashboard if Authenticated)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Role Selection Pages
    Route::get('/login', fn() => view('auth.role-selection', ['action' => 'login']))->name('login');
    Route::get('/register', fn() => view('auth.role-selection', ['action' => 'register']))->name('register');

    // Teacher Authentication
    Route::prefix('teacher')->name('teacher.')->group(function () {
        Route::get('login', [App\Http\Controllers\Auth\Teacher\TeacherLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [App\Http\Controllers\Auth\Teacher\TeacherLoginController::class, 'login']);
        Route::get('register', [App\Http\Controllers\Auth\Teacher\TeacherRegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('register', [App\Http\Controllers\Auth\Teacher\TeacherRegisterController::class, 'register']);
    });

    // Student Authentication
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('login', [App\Http\Controllers\Auth\Student\StudentLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [App\Http\Controllers\Auth\Student\StudentLoginController::class, 'login']);
        Route::post('pin-login', [App\Http\Controllers\Auth\Student\StudentLoginController::class, 'pinLogin'])->name('pin-login');
        Route::get('register', [App\Http\Controllers\Auth\Student\StudentRegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('register', [App\Http\Controllers\Auth\Student\StudentRegisterController::class, 'register']);
    });
});

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

    Route::prefix('quizzes')->name('quizzes.')->group(function () {
        // AI Text Generation
        Route::post('/generate-text', [QuizController::class, 'generateText'])->name('generate-text');

        // Question Management
        Route::prefix('{quiz}/questions')->name('questions.')->controller(QuestionController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/bulk-edit', 'bulkEdit')->name('bulk-edit');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::put('/bulk-update', 'bulkUpdate')->name('bulk-update');
            Route::delete('/bulk-delete', 'bulkDelete')->name('bulk-delete');
            Route::get('/{question}/edit', 'edit')->name('edit');
            Route::put('/{question}', 'update')->name('update');
            Route::delete('/{question}', 'destroy')->name('destroy');
            Route::post('/{question}/update-text', 'updateText')->name('update-text');
            Route::post('/{question}/clone', 'clone')->name('clone');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Results Management
    |--------------------------------------------------------------------------
    */
    Route::prefix('results')->name('results.')->controller(ResultController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/quiz/{quiz}', 'quizResults')->name('quiz');
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

/*
|--------------------------------------------------------------------------
| Include Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';