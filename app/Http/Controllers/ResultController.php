<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function show(Result $result)
    {
        // Allow access if:
        // 1. User owns the result
        // 2. Guest has matching token
        if (Auth::check()) {
            if ($result->user_id !== Auth::id()) {
                abort(403);
            }
        } else {
            if ($result->guest_token !== session('guest_token')) {
                abort(403);
            }
        }

        $result->load(['quiz.questions', 'answers.question']);

        return view('results.show', compact('result'));
    }
}