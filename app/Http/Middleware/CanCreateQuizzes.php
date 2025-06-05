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
        if (!Auth::user()->is_admin && Auth::user()->user_type !== 'teacher') {
            abort(403);
        }

        return $next($request);
    }
}