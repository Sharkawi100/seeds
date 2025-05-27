<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\User;
use App\Models\Result;
use App\Models\Question;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_quizzes' => Quiz::count(),
            'total_results' => Result::count(),
            'total_questions' => Question::count(),
            'recent_quizzes' => Quiz::with('user')->latest()->take(5)->get(),
            'recent_results' => Result::with(['user', 'quiz'])->latest()->take(5)->get(),
            'subject_distribution' => Quiz::groupBy('subject')
                ->selectRaw('subject, count(*) as count')
                ->get(),
            'grade_distribution' => Quiz::groupBy('grade_level')
                ->selectRaw('grade_level, count(*) as count')
                ->orderBy('grade_level')
                ->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function reports()
    {
        return view('admin.reports');
    }

    public function settings()
    {
        return view('admin.settings');
    }
}