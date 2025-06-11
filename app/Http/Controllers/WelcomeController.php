<?php
// File: app/Http/Controllers/WelcomeController.php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Result;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    /**
     * Show the application welcome page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Cache data for 30 minutes to improve performance
        $data = Cache::remember('welcome_page_data', 1800, function () {
            return [
                'activeSubjects' => $this->getActiveSubjects(),
                'stats' => $this->getGeneralStats(),
                'growthStats' => $this->getGrowthStats(),
            ];
        });

        return view('welcome', $data);
    }

    /**
     * Handle PIN entry from landing page
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enterPin(Request $request)
    {
        $validated = $request->validate([
            'pin' => 'required|string|size:6'
        ]);

        // Find quiz by PIN (you'll need to add a PIN column to quizzes table)
        $quiz = Quiz::where('pin', $validated['pin'])
            ->where('is_active', true)
            ->first();

        if (!$quiz) {
            return redirect()->back()
                ->with('error', 'رمز الاختبار غير صحيح أو منتهي الصلاحية')
                ->withInput();
        }

        // Redirect to quiz taking page
        // Check if user is guest
        if (!Auth::check()) {
            return redirect()->route('quiz.take', $quiz)
                ->with('show_guest_form', true);
        }
        return redirect()->route('quiz.take', $quiz);
    }

    /**
     * Show demo quiz
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    // In WelcomeController.php
    public function demo()
    {
        $demoQuiz = Quiz::where('is_demo', true)
            ->where('is_active', true)
            ->inRandomOrder()
            ->first();

        if (!$demoQuiz) {
            abort(404, 'الاختبار التجريبي غير متاح حالياً');
        }

        return redirect()->route('quiz.take', $demoQuiz);
    }

    /**
     * Get active subjects for today
     *
     * @return array
     */
    private function getActiveSubjects()
    {
        $subjects = Quiz::where('created_at', '>=', today())
            ->groupBy('subject')
            ->select('subject', DB::raw('count(*) as count'))
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        return $subjects->map(function ($item) {
            $names = [
                'arabic' => 'اللغة العربية',
                'english' => 'اللغة الإنجليزية',
                'hebrew' => 'اللغة العبرية',
                'math' => 'الرياضيات',
                'science' => 'العلوم',
                'history' => 'التاريخ',
                'geography' => 'الجغرافيا',
            ];

            return [
                'name' => $names[$item->subject] ?? $item->subject,
                'count' => $item->count
            ];
        })->toArray();
    }

    /**
     * Get general statistics
     *
     * @return array
     */
    private function getGeneralStats()
    {
        return [
            'total_quizzes' => Quiz::count(),
            'total_attempts' => Result::nonDemo()->whereMonth('created_at', now()->month)->count(),
            'active_schools' => User::where('is_school', true)->where('last_login_at', '>=', now()->subDays(30))->count(),
            'total_questions' => DB::table('questions')->count(),
        ];
    }

    /**
     * Get growth statistics for the week
     *
     * @return array
     */
    private function getGrowthStats()
    {
        // Calculate average improvement per root type this week
        $weekAgo = now()->subWeek();

        $improvements = Result::nonDemo()->where('created_at', '>=', $weekAgo)->select(DB::raw('
                AVG(JSON_EXTRACT(scores, "$.jawhar")) as avg_jawhar,
                AVG(JSON_EXTRACT(scores, "$.zihn")) as avg_zihn,
                AVG(JSON_EXTRACT(scores, "$.waslat")) as avg_waslat,
                AVG(JSON_EXTRACT(scores, "$.roaya")) as avg_roaya
            '))
            ->first();

        // Calculate week-over-week improvement (simulated for now)
        return [
            'jawhar' => [
                'percentage' => round($improvements->avg_jawhar ?? 75),
                'growth' => 15
            ],
            'zihn' => [
                'percentage' => round($improvements->avg_zihn ?? 82),
                'growth' => 22
            ],
            'waslat' => [
                'percentage' => round($improvements->avg_waslat ?? 68),
                'growth' => 18
            ],
            'roaya' => [
                'percentage' => round($improvements->avg_roaya ?? 60),
                'growth' => 12
            ],
        ];
    }
}

// File: app/Http/Controllers/ContactController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactInquiry;

class ContactController extends Controller
{
    /**
     * Show contact form
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('contact');
    }

    /**
     * Handle contact form submission
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'school' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:2000',
            'type' => 'required|in:demo,support,partnership'
        ]);

        // Send email notification
        try {
            Mail::to(config('mail.admin_email', 'admin@iseraj.com'))
                ->send(new ContactInquiry($validated));

            return redirect()->back()
                ->with('success', 'تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إرسال الرسالة. الرجاء المحاولة مرة أخرى.')
                ->withInput();
        }
    }
}