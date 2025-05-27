<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\QuizController as AdminQuizController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/juzoor-model', function () {
    return view('juzoor-model');
})->name('juzoor.model');

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'he', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Guest quiz routes
Route::get('quiz/{quiz}/take', [QuizController::class, 'take'])->name('quiz.take');
Route::post('quiz/{quiz}/submit', [QuizController::class, 'submit'])->name('quiz.submit');
Route::get('results/{result}', [ResultController::class, 'show'])->name('results.show');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Quiz management
    Route::resource('quizzes', QuizController::class);
    Route::resource('quizzes.questions', QuestionController::class)->except(['show']);
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', AdminUserController::class);
    Route::resource('quizzes', AdminQuizController::class);

    // Additional admin routes
    Route::get('reports', [DashboardController::class, 'reports'])->name('reports');
    Route::get('settings', [DashboardController::class, 'settings'])->name('settings');

    // AI Management Routes
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AiManagementController::class, 'index'])->name('index');
        Route::post('/generate', [App\Http\Controllers\Admin\AiManagementController::class, 'generate'])->name('generate');
        Route::post('/quiz/{quiz}/report', [App\Http\Controllers\Admin\AiManagementController::class, 'generateReport'])->name('generateReport');
  
});



require __DIR__ . '/auth.php';