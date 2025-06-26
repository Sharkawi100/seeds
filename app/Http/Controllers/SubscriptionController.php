<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Services\LemonSqueezyService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class SubscriptionController extends Controller
{
    protected $lemonSqueezy;

    public function __construct(LemonSqueezyService $lemonSqueezy)
    {
        $this->lemonSqueezy = $lemonSqueezy;
    }

    public function upgrade()
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        return view('subscription.upgrade', compact('plans'));
    }

    public function publicPlans()
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        return view('plans', compact('plans'));
    }
    public function createCheckout(Request $request)
    {
        $request->validate(['plan_id' => 'required|exists:subscription_plans,id']);

        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        try {
            $checkoutUrl = $this->lemonSqueezy->createCheckout(Auth::user(), $plan);
            return redirect($checkoutUrl);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'فشل في إنشاء رابط الدفع: ' . $e->getMessage());
        }
    }

    public function success()
    {
        return view('subscription.success');
    }

    public function manage()
    {
        $user = Auth::user();
        $subscription = $user->subscription()->first();
        return view('subscription.manage', compact('user', 'subscription'));
    }

    public function webhook(Request $request)
    {
        return $this->lemonSqueezy->handleWebhook($request);
    }
    /**
     * Cancel user's subscription with reason sent to contact system
     */
    public function cancelSubscription(Request $request)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ], [
            'cancellation_reason.required' => 'يجب كتابة سبب إلغاء الاشتراك',
            'cancellation_reason.max' => 'سبب الإلغاء يجب ألا يتجاوز 500 حرف'
        ]);

        $user = Auth::user();
        $subscription = $user->subscription()->first();

        if (!$subscription || !$subscription->isActive()) {
            return redirect()->back()->with('error', 'لا يوجد اشتراك نشط للإلغاء');
        }

        if ($subscription->isCancelled()) {
            return redirect()->back()->with('error', 'تم إلغاء الاشتراك مسبقاً');
        }

        // Cancel subscription via LemonSqueezy
        $success = $this->lemonSqueezy->cancelSubscription($subscription);

        if ($success) {
            // Create contact message for cancellation reason
            $this->createCancellationContactMessage($user, $subscription, $request->cancellation_reason);

            return redirect()->back()->with('success', 'تم إلغاء الاشتراك بنجاح. ستبقى الخدمة متاحة حتى انتهاء الفترة المدفوعة.');
        }

        return redirect()->back()->with('error', 'فشل في إلغاء الاشتراك. يرجى المحاولة مرة أخرى أو التواصل مع الدعم.');
    }

    /**
     * Create contact message for subscription cancellation
     */
    private function createCancellationContactMessage($user, $subscription, $reason)
    {
        try {
            // Get cancellation category ID
            $cancellationCategory = \DB::table('contact_categories')
                ->where('name_ar', 'إلغاء الاشتراك')
                ->first();

            if (!$cancellationCategory) {
                \Log::error('Cancellation contact category not found');
                return;
            }

            // Create contact message
            \DB::table('contact_messages')->insert([
                'name' => $user->name,
                'email' => $user->email,
                'category_id' => $cancellationCategory->id,
                'subscription_id' => $subscription->id,
                'subject' => 'إلغاء اشتراك - ' . ($subscription->plan_name ?? 'خطة غير محددة'),
                'message' => "قام المستخدم بإلغاء اشتراكه.\n\n" .
                    "تفاصيل الاشتراك:\n" .
                    "- الخطة: " . ($subscription->plan_name ?? 'غير محددة') . "\n" .
                    "- تاريخ البدء: " . $subscription->current_period_start->format('Y-m-d') . "\n" .
                    "- تاريخ الانتهاء: " . $subscription->current_period_end->format('Y-m-d') . "\n" .
                    "- تاريخ الإلغاء: " . now()->format('Y-m-d H:i') . "\n\n" .
                    "سبب الإلغاء:\n" . $reason,
                'is_read' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            \Log::info('Cancellation contact message created', [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to create cancellation contact message', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
        }
    }

}