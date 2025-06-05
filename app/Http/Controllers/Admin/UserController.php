<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Updated role filtering to handle user_type
        if ($request->filled('role')) {
            if ($request->role === 'admin') {
                $query->where('is_admin', true);
            } elseif (in_array($request->role, ['teacher', 'student'])) {
                $query->where('user_type', $request->role);
            }
        }

        $users = $query->withCount(['quizzes', 'results'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'user_type' => ['required', 'in:student,teacher'],
            'is_admin' => ['boolean'],
            'school_name' => ['nullable', 'string', 'max:255'],
            'grade_level' => ['nullable', 'integer', 'min:1', 'max:9'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_admin'] = $request->boolean('is_admin');

        // Only save school_name for teachers
        if ($validated['user_type'] !== 'teacher') {
            $validated['school_name'] = null;
        }

        // Only save grade_level for students
        if ($validated['user_type'] !== 'student') {
            $validated['grade_level'] = null;
        }

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    public function show(User $user)
    {
        $user->loadCount(['quizzes', 'results']);
        $recentQuizzes = $user->quizzes()->latest()->take(5)->get();
        $recentResults = $user->results()->with('quiz')->latest()->take(10)->get();

        return view('admin.users.show', compact('user', 'recentQuizzes', 'recentResults'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'user_type' => ['required', 'in:student,teacher'],
            'is_admin' => ['boolean'],
            'school_name' => ['nullable', 'string', 'max:255'],
            'grade_level' => ['nullable', 'integer', 'min:1', 'max:9'],
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_admin'] = $request->boolean('is_admin');

        // Prevent removing admin role from self
        if ($user->id === Auth::id() && !$validated['is_admin']) {
            return back()->with('error', 'لا يمكنك إزالة صلاحيات الإدارة من حسابك');
        }

        // Only save school_name for teachers
        if ($validated['user_type'] !== 'teacher') {
            $validated['school_name'] = null;
        }

        // Only save grade_level for students
        if ($validated['user_type'] !== 'student') {
            $validated['grade_level'] = null;
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'لا يمكنك حذف حسابك');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }

    // NEW: Impersonate user (login as another user)
    public function impersonate(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'لا يمكنك انتحال شخصية نفسك');
        }

        // Store the admin's ID to return later
        Session::put('impersonator', Auth::id());

        // Login as the selected user
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('info', 'أنت الآن تتصفح كـ: ' . $user->name);
    }

    // NEW: Stop impersonation
    public function stopImpersonation()
    {
        if (!Session::has('impersonator')) {
            return redirect()->route('dashboard');
        }

        $originalUserId = Session::get('impersonator');
        Session::forget('impersonator');

        // Use the simplified name since User is already imported
        $originalUser = User::find($originalUserId);

        if ($originalUser && $originalUser instanceof User) {
            Auth::login($originalUser);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'تم العودة إلى حسابك الأصلي');
    }
}