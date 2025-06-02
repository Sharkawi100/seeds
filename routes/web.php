<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\QuizController as AdminQuizController;
use App\Http\Controllers\Admin\AiManagementController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

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
| Public Routes - No Authentication Required
|--------------------------------------------------------------------------
*/

// Home page
Route::get('/', [WelcomeController::class, 'index'])->name('home');

// Static pages
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/juzoor-model', function () {
    return view('juzoor-model');
})->name('juzoor.model');

Route::get('/question-guide', function () {
    return view('question-guide');
})->name('question.guide');

// Contact routes
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Language switcher
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'he', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

/*
|--------------------------------------------------------------------------
| Quiz Routes - Public Access
|--------------------------------------------------------------------------
*/

Route::prefix('quiz')->name('quiz.')->group(function () {
    // Guest quiz access
    Route::post('/enter-pin', [WelcomeController::class, 'enterPin'])->name('enter-pin');
    Route::get('/demo', [WelcomeController::class, 'demo'])->name('demo');

    // Quiz taking (no auth required)
    Route::get('/{quiz}/take', [QuizController::class, 'take'])->name('take');
    Route::post('/{quiz}/submit', [QuizController::class, 'submit'])->name('submit');
});

// Results viewing (accessible by guest with token or authenticated users)
Route::get('/results/{result}', [ResultController::class, 'show'])->name('results.show');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Quiz management routes
    Route::prefix('quizzes')->name('quizzes.')->group(function () {
        // CRUD operations
        Route::get('/', [QuizController::class, 'index'])->name('index');
        Route::get('/create', [QuizController::class, 'create'])->name('create');
        Route::post('/', [QuizController::class, 'store'])->name('store');
        Route::get('/{quiz}', [QuizController::class, 'show'])->name('show');
        Route::get('/{quiz}/edit', [QuizController::class, 'edit'])->name('edit');
        Route::put('/{quiz}', [QuizController::class, 'update'])->name('update');
        Route::delete('/{quiz}', [QuizController::class, 'destroy'])->name('destroy');

        // AI text generation
        Route::post('/generate-text', [QuizController::class, 'generateText'])->name('generate-text');

        // Question management for quizzes
        Route::prefix('{quiz}/questions')->name('questions.')->group(function () {
            Route::get('/', [QuestionController::class, 'index'])->name('index');
            Route::get('/create', [QuestionController::class, 'create'])->name('create');
            Route::post('/', [QuestionController::class, 'store'])->name('store');
            Route::get('/{question}/edit', [QuestionController::class, 'edit'])->name('edit');
            Route::put('/{question}', [QuestionController::class, 'update'])->name('update');
            Route::delete('/{question}', [QuestionController::class, 'destroy'])->name('destroy');

            // Inline question text update
            Route::post('/{question}/update-text', [QuestionController::class, 'updateText'])->name('update-text');
        });
    });

    // Results management
    Route::prefix('results')->name('results.')->group(function () {
        Route::get('/', [ResultController::class, 'index'])->name('index');
        Route::get('/quiz/{quiz}', [ResultController::class, 'quizResults'])->name('quiz');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Admin dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');

    // User management
    Route::resource('users', AdminUserController::class);

    // Quiz management (admin view)
    Route::resource('quizzes', AdminQuizController::class);

    // AI Management
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::get('/', [AiManagementController::class, 'index'])->name('index');
        Route::post('/generate', [AiManagementController::class, 'generate'])->name('generate');
        Route::post('/quiz/{quiz}/report', [AiManagementController::class, 'generateReport'])->name('generateReport');
    });
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::post('/profile/logout-other-devices', [ProfileController::class, 'logoutOtherDevices'])
    ->name('profile.logout-other-devices')
    ->middleware('auth');

require __DIR__ . '/auth.php';