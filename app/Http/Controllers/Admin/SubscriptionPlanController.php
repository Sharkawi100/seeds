<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;


class SubscriptionPlanController extends Controller
{
    public function index()
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        $plans = SubscriptionPlan::orderBy('created_at')->get();
        return view('admin.subscription-plans.index', compact('plans'));
    }

    public function create()
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        return view('admin.subscription-plans.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'lemon_squeezy_variant_id' => 'required|string|max:255',
            'monthly_quiz_limit' => 'required|integer|min:1|max:255',
            'monthly_ai_text_limit' => 'required|integer|min:0|max:255',
            'monthly_ai_quiz_limit' => 'required|integer|min:0|max:255',
            'price_monthly' => 'required|numeric|min:0|max:999.99',
            'is_active' => 'boolean'
        ]);

        SubscriptionPlan::create($validated);

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', 'تم إنشاء الخطة بنجاح');
    }
    public function show(SubscriptionPlan $subscriptionPlan)
    {
        $activeSubscriptions = $subscriptionPlan->subscriptions()
            ->with('user')
            ->where('status', 'active')
            ->latest()
            ->paginate(10);

        $stats = [
            'total_subscriptions' => $subscriptionPlan->subscriptions()->count(),
            'active_subscriptions' => $subscriptionPlan->subscriptions()->where('status', 'active')->count(),
            'monthly_revenue' => $subscriptionPlan->subscriptions()->where('status', 'active')->count() * $subscriptionPlan->price_monthly,
        ];

        return view('admin.subscription-plans.show', compact('subscriptionPlan', 'activeSubscriptions', 'stats'));
    }

    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        return view('admin.subscription-plans.edit', compact('subscriptionPlan'));
    }

    public function update(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'lemon_squeezy_variant_id' => 'required|string|max:255',
            'monthly_quiz_limit' => 'required|integer|min:1|max:255',
            'monthly_ai_text_limit' => 'required|integer|min:0|max:255',
            'monthly_ai_quiz_limit' => 'required|integer|min:0|max:255',
            'price_monthly' => 'required|numeric|min:0|max:999.99',
            'is_active' => 'boolean'
        ]);

        $subscriptionPlan->update($validated);

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', 'تم تحديث الخطة بنجاح');
    }

    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        // Check if plan has active subscriptions
        if ($subscriptionPlan->subscriptions()->where('status', 'active')->exists()) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف خطة لديها اشتراكات نشطة');
        }

        $subscriptionPlan->delete();

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', 'تم حذف الخطة بنجاح');
    }

    public function toggle(SubscriptionPlan $subscriptionPlan)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        $subscriptionPlan->update([
            'is_active' => !$subscriptionPlan->is_active
        ]);

        $status = $subscriptionPlan->is_active ? 'تفعيل' : 'إلغاء تفعيل';
        return redirect()->back()->with('success', "تم {$status} الخطة بنجاح");
    }
    public function users(Request $request)
    {
        // Get filter parameters
        $status = $request->get('status');
        $planId = $request->get('plan');

        // Build query
        $query = Subscription::with(['user', 'subscriptionPlan']);

        if ($status) {
            if ($status === 'expired') {
                $query->where('current_period_end', '<', now());
            } else {
                $query->where('status', $status);
            }
        }

        if ($planId) {
            $query->where('plan_id', $planId);
        }

        $subscriptions = $query->latest()->paginate(20);

        // Calculate stats
        $stats = [
            'active' => Subscription::where('status', 'active')->count(),
            'cancelled' => Subscription::where('status', 'cancelled')->count(),
            'expired' => Subscription::where('current_period_end', '<', now())->count(),
            'revenue' => Subscription::join('subscription_plans', 'subscriptions.plan_id', '=', 'subscription_plans.id')
                ->where('subscriptions.status', 'active')
                ->sum('subscription_plans.price_monthly')
        ];

        $plans = SubscriptionPlan::all();

        return view('admin.subscription-plans.users', compact('subscriptions', 'stats', 'plans'));
    }
    public function manageUserSubscription($userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        $plans = SubscriptionPlan::where('is_active', true)->get();
        $currentSubscription = $user->subscription;

        return view('admin.subscription-plans.manage-user', compact('user', 'plans', 'currentSubscription'));
    }

    public function updateUserSubscription(Request $request, $userId)
    {
        $user = \App\Models\User::findOrFail($userId);

        $validated = $request->validate([
            'action' => 'required|in:grant,revoke,update',
            'plan_id' => 'required_if:action,grant,update|exists:subscription_plans,id',
            'expires_at' => 'required_if:action,grant,update|date|after:today',
        ]);

        \DB::beginTransaction();
        try {
            if ($validated['action'] === 'revoke') {
                // Remove subscription
                $user->update([
                    'subscription_active' => false,
                    'subscription_expires_at' => null,
                    'subscription_plan' => null,
                    'subscription_status' => null,
                ]);

                // Deactivate existing subscription record
                if ($user->subscription) {
                    $user->subscription->update(['status' => 'cancelled']);
                }

                \DB::commit();
                return redirect()->back()->with('success', 'تم إلغاء اشتراك المستخدم بنجاح');
            }

            $plan = SubscriptionPlan::find($validated['plan_id']);

            // Prepare subscription data
            $subscriptionData = [
                'user_id' => $user->id,
                'lemon_squeezy_subscription_id' => 'admin_' . $user->id . '_' . time(),
                'lemon_squeezy_customer_id' => 'admin_customer_' . $user->id,
                'status' => 'active',
                'plan_name' => $plan->name,
                'plan_id' => $plan->id,
                'current_period_start' => now(),
                'current_period_end' => $validated['expires_at'],
            ];

            // Create or update subscription record FIRST
            if ($user->subscription) {
                $user->subscription->update($subscriptionData);
            } else {
                \App\Models\Subscription::create($subscriptionData);
            }

            // Then sync user table with subscription data
            try {
                $user->update([
                    'subscription_active' => true,
                    'subscription_expires_at' => $validated['expires_at'],
                    'subscription_plan' => $plan->name,
                    'subscription_status' => 'active',
                ]);
                \Log::info('User table updated successfully', ['user_id' => $user->id]);

                // Add this - check if update actually worked
                $user->refresh();
                \Log::info('User state after update', [
                    'user_id' => $user->id,
                    'subscription_active' => $user->subscription_active,
                    'subscription_expires_at' => $user->subscription_expires_at
                ]);

            } catch (\Exception $e) {
                \Log::error('User table update failed', ['user_id' => $user->id, 'error' => $e->getMessage()]);
                throw $e;
            }

            // Add this right before \DB::commit()
            \Log::info('About to commit transaction', ['user_id' => $user->id]);

            // Create monthly quota if it doesn't exist
            \App\Models\MonthlyQuota::firstOrCreate([
                'user_id' => $user->id,
                'year' => now()->year,
                'month' => now()->month,
            ], [
                'quiz_count' => 0,
                'ai_text_requests' => 0,
                'ai_quiz_requests' => 0,
            ]);

            \DB::commit();
            return redirect()->back()->with('success', 'تم تحديث اشتراك المستخدم بنجاح');

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Subscription update failed', ['error' => $e->getMessage(), 'user_id' => $userId]);
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث الاشتراك');
        }
    }
}