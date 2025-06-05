<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Result;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $query = Quiz::with(['user', 'questions'])
            ->withCount('questions');

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('pin', $request->search);
            });
        }

        // Filters
        if ($request->filled('subject')) {
            $query->where('subject', $request->subject);
        }

        if ($request->filled('grade')) {
            $query->where('grade_level', $request->grade);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $quizzes = $query->latest()->paginate(20)->withQueryString();

        // Statistics
        $stats = [
            'total' => Quiz::count(),
            'active' => Quiz::where('is_active', true)->count(),
            'total_attempts' => Result::count(),
            'this_week' => Quiz::where('created_at', '>=', now()->subDays(7))->count()
        ];

        return view('admin.quizzes.index', compact('quizzes', 'stats'));
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

    public function toggleStatus(Quiz $quiz)
    {
        $quiz->update(['is_active' => !$quiz->is_active]);
        return response()->json(['success' => true]);
    }

    public function export(Request $request)
    {
        $query = Quiz::with(['user', 'questions']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('pin', $request->search);
            });
        }

        if ($request->filled('subject')) {
            $query->where('subject', $request->subject);
        }

        if ($request->filled('grade')) {
            $query->where('grade_level', $request->grade);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $quizzes = $query->get();

        $csv = "العنوان,المادة,الصف,عدد الأسئلة,المنشئ,الحالة,PIN,تاريخ الإنشاء\n";

        foreach ($quizzes as $quiz) {
            $csv .= implode(',', [
                $quiz->title,
                ['arabic' => 'العربية', 'english' => 'الإنجليزية', 'hebrew' => 'العبرية'][$quiz->subject],
                $quiz->grade_level,
                $quiz->questions->count(),
                $quiz->user->name ?? 'غير معروف',
                $quiz->is_active ? 'نشط' : 'غير نشط',
                $quiz->pin,
                $quiz->created_at->format('Y-m-d')
            ]) . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="quizzes_' . date('Y-m-d') . '.csv"');
    }
}