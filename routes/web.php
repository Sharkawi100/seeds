<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\QuizController as AdminQuizController;
use App\Http\Controllers\Admin\AiManagementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/juzoor-model', function () {
    return view('juzoor-model');
})->name('juzoor.model');

// Language switcher
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'he', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Guest quiz routes (publicly accessible)
Route::prefix('quiz')->name('quiz.')->group(function () {
    Route::get('{quiz}/take', [QuizController::class, 'take'])->name('take');
    Route::post('{quiz}/submit', [QuizController::class, 'submit'])->name('submit');
});

Route::get('results/{result}', [ResultController::class, 'show'])->name('results.show');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Quiz management
    Route::resource('quizzes', QuizController::class);
    Route::resource('quizzes.questions', QuestionController::class)->except(['show']);

    // TEXT GENERATION
    Route::post('quizzes/generate-text', [QuizController::class, 'generateText'])
        ->name('quizzes.generate-text');

    Route::post('quizzes/{quiz}/questions/{question}/update-text', [QuestionController::class, 'updateText'])
        ->name('quizzes.questions.update-text');

    Route::post('quizzes/{quiz}/questions/{question}/update-text', [QuestionController::class, 'updateText'])
        ->name('quizzes.questions.update-text');

    // Generate text route for quiz creation
    Route::post('quizzes/generate-text', [QuizController::class, 'generateText'])
        ->name('quizzes.generate-text');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User management
    Route::resource('users', AdminUserController::class);

    // Quiz management
    Route::resource('quizzes', AdminQuizController::class);

    // Additional admin routes
    Route::get('reports', [DashboardController::class, 'reports'])->name('reports');
    Route::get('settings', [DashboardController::class, 'settings'])->name('settings');
    Route::post('/quizzes/generate-text', [QuizController::class, 'generateText'])
        ->name('quizzes.generate-text')
        ->middleware('auth');
    // AI Management Routes
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::get('/', [AiManagementController::class, 'index'])->name('index');
        Route::post('/generate', [AiManagementController::class, 'generate'])->name('generate');
        Route::post('/quiz/{quiz}/report', [AiManagementController::class, 'generateReport'])->name('generateReport');
    });
});

require __DIR__ . '/auth.php';