<?php
// File: app/Http/Controllers/Admin/UserController.php

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

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Role filtering
        if ($request->filled('role')) {
            if ($request->role === 'admin') {
                $query->where('is_admin', true);
            } elseif (in_array($request->role, ['teacher', 'student'])) {
                $query->where('user_type', $request->role);
            }
        }

        // Status filtering
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $users = $query->withCount(['quizzes', 'results'])
            ->with('deactivatedBy')
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
            'is_active' => ['boolean'],
            'school_name' => ['nullable', 'string', 'max:255'],
            'grade_level' => ['nullable', 'integer', 'min:1', 'max:9'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_admin'] = $request->boolean('is_admin');
        $validated['is_active'] = $request->boolean('is_active', true);

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
        $recentQuizzes = $user->quizzes()->with('subject')->latest()->take(5)->get();
        $recentResults = $user->results()->with('quiz')->latest()->take(10)->get();

        // Get login history from sessions table if available
        $loginHistory = collect(); // Empty collection for now

        return view('admin.users.show', compact('user', 'recentQuizzes', 'recentResults', 'loginHistory'));
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
            'is_active' => ['boolean'],
            'school_name' => ['nullable', 'string', 'max:255'],
            'grade_level' => ['nullable', 'integer', 'min:1', 'max:9'],
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_admin'] = $request->boolean('is_admin');
        $validated['is_active'] = $request->boolean('is_active');

        // Prevent removing admin role from self
        if ($user->id === Auth::id() && !$validated['is_admin']) {
            return back()->with('error', 'لا يمكنك إزالة صلاحيات الإدارة من حسابك');
        }

        // Prevent deactivating self
        if ($user->id === Auth::id() && !$validated['is_active']) {
            return back()->with('error', 'لا يمكنك تعطيل حسابك');
        }

        // Handle deactivation
        if (!$validated['is_active'] && $user->is_active) {
            $user->deactivate($request->input('deactivation_reason'), Auth::id());
            unset($validated['is_active']); // Already handled by deactivate method
        } elseif ($validated['is_active'] && !$user->is_active) {
            $user->activate();
            unset($validated['is_active']); // Already handled by activate method
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

        // Soft delete to preserve data integrity
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }

    /**
     * Toggle user active status via AJAX
     */
    public function toggleStatus(User $user)
    {
        if ($user->id === Auth::id()) {
            return response()->json(['error' => 'لا يمكنك تعطيل حسابك'], 403);
        }

        if ($user->is_active) {
            $user->deactivate('معطل بواسطة المدير', Auth::id());
            $message = 'تم تعطيل الحساب';
        } else {
            $user->activate();
            $message = 'تم تفعيل الحساب';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_active' => $user->is_active
        ]);
    }

    /**
     * Update user role
     */
    public function updateRole(Request $request, User $user)
    {
        if ($user->id === Auth::id()) {
            return response()->json(['error' => 'لا يمكنك تغيير دورك'], 403);
        }

        $validated = $request->validate([
            'user_type' => ['required', 'in:student,teacher'],
            'is_admin' => ['boolean']
        ]);

        $user->update([
            'user_type' => $validated['user_type'],
            'is_admin' => $validated['is_admin'] ?? false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الدور بنجاح',
            'role_display' => $user->role_display
        ]);
    }

    /**
     * Impersonate user (login as another user)
     */
    public function impersonate(User $user)
    {
        if (!$user->canBeImpersonated()) {
            return back()->with('error', 'لا يمكن انتحال شخصية هذا المستخدم');
        }

        // Store the admin's ID to return later
        Session::put('impersonator', Auth::id());
        Session::put('impersonator_name', Auth::user()->name);

        // Login as the selected user - FIXED: use $user not collection
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('info', 'أنت الآن تتصفح كـ: ' . $user->name);
    }

    /**
     * Stop impersonation and return to admin account
     */
    public function stopImpersonation()
    {
        if (!Session::has('impersonator')) {
            return redirect()->route('dashboard');
        }

        $originalUserId = Session::get('impersonator');
        Session::forget('impersonator');
        Session::forget('impersonator_name');

        $originalUser = User::find($originalUserId);
        if ($originalUser && $originalUser->is_admin) {
            Auth::login($originalUser);
            return redirect()->route('admin.users.index')
                ->with('success', 'تم العودة إلى حسابك الأصلي');
        }

        // Fallback logout if something goes wrong
        Auth::logout();
        return redirect()->route('login');
    }

    /**
     * Disconnect social account
     */
    public function disconnectSocial(Request $request, User $user)
    {
        $validated = $request->validate([
            'provider' => ['required', 'in:google']
        ]);

        $provider = $validated['provider'];
        $field = $provider . '_id';

        // Check if this is their primary auth method
        if ($user->auth_provider === $provider && !$user->password) {
            return response()->json([
                'error' => 'لا يمكن فصل طريقة الدخول الأساسية بدون كلمة مرور'
            ], 422);
        }

        $user->update([$field => null]);

        return response()->json([
            'success' => true,
            'message' => 'تم فصل حساب ' . ucfirst($provider) . ' بنجاح'
        ]);
    }

    /**
     * Export users data
     */
    public function export(Request $request)
    {
        $users = User::withCount(['quizzes', 'results'])
            ->when($request->filled('role'), function ($q) use ($request) {
                if ($request->role === 'admin') {
                    $q->where('is_admin', true);
                } else {
                    $q->where('user_type', $request->role);
                }
            })
            ->get();

        $csv = "الاسم,البريد الإلكتروني,النوع,الحالة,عدد الاختبارات,عدد النتائج,تاريخ التسجيل\n";

        foreach ($users as $user) {
            $csv .= implode(',', [
                $user->name,
                $user->email,
                $user->role_display,
                $user->is_active ? 'نشط' : 'معطل',
                $user->quizzes_count,
                $user->results_count,
                $user->created_at->format('Y-m-d')
            ]) . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="users_' . date('Y-m-d') . '.csv"');
    }
}