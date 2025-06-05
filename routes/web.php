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
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

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
Route::get('/question-guide', fn() => view('question-guide'))->name('question.guide');

// Contact
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

/*
|--------------------------------------------------------------------------
| Quiz Routes (Public Access)
|--------------------------------------------------------------------------
*/

Route::prefix('quiz')->name('quiz.')->group(function () {
    // Guest Access
    Route::post('/enter-pin', [WelcomeController::class, 'enterPin'])->name('enter-pin');
    Route::get('/demo', [WelcomeController::class, 'demo'])->name('demo');

    // Quiz Taking (No Auth Required)
    Route::controller(QuizController::class)->group(function () {
        Route::get('/{quiz}/take', 'take')->name('take');
        Route::post('/{quiz}/submit', 'submit')->name('submit');
    });
});

// Results Viewing (Guest with Token or Authenticated)
Route::get('/results/{result}', [ResultController::class, 'show'])->name('results.show');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Profile Management
    |--------------------------------------------------------------------------
    */

    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        // Main Profile Routes
        Route::get('/', 'dashboard')->name('dashboard');
        Route::get('/edit', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');

        // Profile Features
        Route::post('/avatar', 'updateAvatar')->name('avatar.update');
        Route::get('/completion', 'completion')->name('completion');
        Route::get('/sessions', 'sessions')->name('sessions');
        Route::post('/logout-other-devices', 'logoutOtherDevices')->name('logout-other-devices');

        // Settings
        Route::patch('/preferences', 'updatePreferences')->name('preferences');
        Route::patch('/privacy', 'updatePrivacy')->name('privacy');
    });

    // Password Update (Separate Controller)
    Route::put('/profile/password', [PasswordController::class, 'update'])->name('password.update');

    /*
    |--------------------------------------------------------------------------
    | Quiz Management
    |--------------------------------------------------------------------------
    */

    Route::resource('quizzes', QuizController::class);

    Route::prefix('quizzes')->name('quizzes.')->group(function () {
        // AI Text Generation
        Route::post('/generate-text', [QuizController::class, 'generateText'])->name('generate-text');

        // Question Management
        Route::prefix('{quiz}/questions')->name('questions.')->controller(QuestionController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{question}/edit', 'edit')->name('edit');
            Route::put('/{question}', 'update')->name('update');
            Route::delete('/{question}', 'destroy')->name('destroy');
            Route::post('/{question}/update-text', 'updateText')->name('update-text');
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
| Admin Routes
|--------------------------------------------------------------------------
*/



Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Admin Dashboard
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/reports', 'reports')->name('reports');
        Route::get('/settings', 'settings')->name('settings');
    });

    // Resource Management
    Route::resource('users', AdminUserController::class);
    Route::resource('quizzes', AdminQuizController::class);

    // AI Management
    Route::prefix('ai')->name('ai.')->controller(AiManagementController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/generate', 'generate')->name('generate');
        Route::post('/quiz/{quiz}/report', 'generateReport')->name('generateReport');
    });
});

// Simple logout route that works with GET
Route::get('/logout-now', function () {
    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout.simple');
require __DIR__ . '/auth.php';