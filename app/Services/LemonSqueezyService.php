<?php

namespace App\Services;

use App\Models\User;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LemonSqueezyService
{
    protected $apiKey;
    protected $storeId;
    protected $signingSecret;

    public function __construct()
    {
        $this->apiKey = config('services.lemonsqueezy.api_key');
        $this->storeId = config('services.lemonsqueezy.store_id');
        $this->signingSecret = config('services.lemonsqueezy.signing_secret');
    }

    public function createCheckout(User $user, SubscriptionPlan $plan): string
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json',
        ])->post('https://api.lemonsqueezy.com/v1/checkouts', [
                    'data' => [
                        'type' => 'checkouts',
                        'attributes' => [
                            'product_options' => [
                                'enabled_variants' => [$plan->lemon_squeezy_variant_id]
                            ],
                            'checkout_options' => [
                                'embed' => false,
                                'media' => false,
                                'logo' => true,
                            ],
                            'checkout_data' => [
                                'email' => $user->email,
                                'name' => $user->name,
                                'custom' => [
                                    'user_id' => (string) $user->id,
                                    'plan_id' => (string) $plan->id
                                ]
                            ],
                            'expires_at' => now()->addHour()->toISOString(),
                        ],
                        'relationships' => [
                            'store' => [
                                'data' => [
                                    'type' => 'stores',
                                    'id' => $this->storeId
                                ]
                            ],
                            'variant' => [
                                'data' => [
                                    'type' => 'variants',
                                    'id' => $plan->lemon_squeezy_variant_id
                                ]
                            ]
                        ]
                    ]
                ]);

        if ($response->successful()) {
            return $response->json('data.attributes.url');
        }

        throw new \Exception('فشل في إنشاء رابط الدفع: ' . $response->body());
    }

    public function handleWebhook(Request $request)
    {
        $signature = $request->header('X-Signature');
        $payload = $request->getContent();

        if (!$this->verifyWebhookSignature($signature, $payload)) {
            return response('Unauthorized', 401);
        }

        $data = $request->json()->all();
        $eventName = $data['meta']['event_name'];

        switch ($eventName) {
            case 'subscription_created':
                $this->handleSubscriptionCreated($data['data']);
                break;
            case 'subscription_updated':
                $this->handleSubscriptionUpdated($data['data']);
                break;
            case 'subscription_cancelled':
                $this->handleSubscriptionCancelled($data['data']);
                break;
        }

        return response('OK', 200);
    }

    protected function verifyWebhookSignature($signature, $payload): bool
    {
        if (!$this->signingSecret) {
            return false;
        }

        $expectedSignature = hash_hmac('sha256', $payload, $this->signingSecret);
        return hash_equals($expectedSignature, $signature);
    }

    protected function handleSubscriptionCreated($subscriptionData)
    {
        $customData = $subscriptionData['attributes']['custom_data'] ?? [];
        $userId = $customData['user_id'] ?? null;
        $planId = $customData['plan_id'] ?? null;

        if (!$userId) {
            Log::warning('No user_id in subscription webhook');
            return;
        }

        $user = User::find($userId);
        $plan = SubscriptionPlan::find($planId);

        if ($user) {
            $user->update([
                'subscription_active' => true,
                'subscription_expires_at' => $subscriptionData['attributes']['renews_at'],
                'lemon_squeezy_customer_id' => $subscriptionData['attributes']['customer_id']
            ]);

            Subscription::create([
                'user_id' => $user->id,
                'lemon_squeezy_subscription_id' => $subscriptionData['id'],
                'lemon_squeezy_customer_id' => $subscriptionData['attributes']['customer_id'],
                'status' => $subscriptionData['attributes']['status'],
                'plan_name' => $plan->name ?? 'Pro Teacher',
                'plan_id' => $planId,
                'current_period_start' => $subscriptionData['attributes']['created_at'],
                'current_period_end' => $subscriptionData['attributes']['renews_at'],
            ]);
        }
    }

    protected function handleSubscriptionUpdated($subscriptionData)
    {
        $subscription = Subscription::where('lemon_squeezy_subscription_id', $subscriptionData['id'])->first();

        if ($subscription) {
            $subscription->update([
                'status' => $subscriptionData['attributes']['status'],
                'current_period_end' => $subscriptionData['attributes']['renews_at'],
            ]);

            $subscription->user->update([
                'subscription_active' => $subscriptionData['attributes']['status'] === 'active',
                'subscription_expires_at' => $subscriptionData['attributes']['renews_at'],
            ]);
        }
    }

    protected function handleSubscriptionCancelled($subscriptionData)
    {
        $subscription = Subscription::where('lemon_squeezy_subscription_id', $subscriptionData['id'])->first();

        if ($subscription) {
            $subscription->update(['status' => 'cancelled']);
            $subscription->user->update(['subscription_active' => false]);
        }
    }
}