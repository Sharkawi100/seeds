<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequireSubscription
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->canUseAI()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'يتطلب هذا الاستخدام اشتراك نشط',
                    'upgrade_required' => true
                ], 403);
            }

            return redirect()->route('subscription.upgrade')
                ->with('error', 'يتطلب استخدام الذكاء الاصطناعي اشتراك نشط');
        }

        return $next($request);
    }
}