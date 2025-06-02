<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with(['user', 'questions'])
            ->withCount('questions')
            ->latest()
            ->paginate(20);

        return view('admin.quizzes.index', compact('quizzes'));
    }

    public function show(Quiz $quiz)
    {
        $quiz->load(['user', 'questions', 'results']);
        return view('admin.quizzes.show', compact('quiz'));
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('admin.quizzes.index')
            ->with('success', 'تم حذف الاختبار بنجاح');
    }
}