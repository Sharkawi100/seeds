<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Services\LemonSqueezyService;
use Illuminate\Support\Facades\Auth;

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
        $subscription = $user->subscription;
        return view('subscription.manage', compact('user', 'subscription'));
    }

    public function webhook(Request $request)
    {
        return $this->lemonSqueezy->handleWebhook($request);
    }
}