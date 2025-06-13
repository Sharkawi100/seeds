<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::ordered()->get();
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('admin.subjects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191|unique:subjects',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Subject::create($validated);

        return redirect()->route('admin.subjects.index')->with('success', 'تم إضافة المادة بنجاح');
    }

    public function edit(Subject $subject)
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191|unique:subjects,name,' . $subject->id,
            'sort_order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $subject->update($validated);

        return redirect()->route('admin.subjects.index')->with('success', 'تم تحديث المادة بنجاح');
    }

    public function destroy(Subject $subject)
    {
        if ($subject->quizzes()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف المادة لوجود اختبارات مرتبطة بها');
        }

        $subject->delete();
        return back()->with('success', 'تم حذف المادة بنجاح');
    }

    public function toggleStatus(Subject $subject)
    {
        $subject->update(['is_active' => !$subject->is_active]);

        $status = $subject->is_active ? 'تفعيل' : 'إلغاء تفعيل';
        return back()->with('success', "تم {$status} المادة بنجاح");
    }
}