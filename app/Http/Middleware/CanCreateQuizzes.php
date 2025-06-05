<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CanCreateQuizzes
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Only teachers and admins can create quizzes
        if (Auth::user()->user_type !== 'teacher' && !Auth::user()->is_admin) {
            abort(403, 'غير مصرح لك بإنشاء اختبارات. هذه الميزة متاحة للمعلمين فقط.');
        }

        return $next($request);
    }
}