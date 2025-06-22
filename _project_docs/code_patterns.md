# Code Patterns & Conventions - جُذور (Juzoor)

Last Updated: June 22, 2025

## Naming Conventions

### PHP/Laravel

-   **Models**: Singular PascalCase (User, Quiz, Question, Result, Answer, Subscription, SubscriptionPlan, MonthlyQuota)
-   **Controllers**: PascalCase + Controller suffix
    -   Web: QuizController, QuestionController, ResultController, SubscriptionController
    -   Auth: AuthenticatedSessionController, RegisteredUserController
    -   Admin: Admin\UserController, Admin\QuizController, Admin\SubscriptionPlanController
-   **Database Tables**: Plural snake_case (users, quizzes, questions, results, answers, subscriptions, subscription_plans, monthly_quotas)
-   **Database Columns**: snake_case (user_id, quiz_id, root_type, depth_level, guest_token, subscription_active, lemon_squeezy_customer_id)
-   **Routes**: kebab-case with dot notation (quizzes.index, quiz.take, subscription.upgrade, admin.subscription-plans.users)

### Juzoor-Specific Terms

-   **Root Types**: Always lowercase English in code: `jawhar`, `zihn`, `waslat`, `roaya`
-   **Arabic Display**: جَوهر (Jawhar), ذِهن (Zihn), وَصلات (Waslat), رُؤية (Roaya)
-   **Depth Levels**: Integers 1, 2, 3
-   **Subjects**: Database uses subject_id (foreign key to subjects table)

### Frontend

-   **Blade Views**: kebab-case.blade.php (quiz-results.blade.php, guest-info.blade.php, subscription-upgrade.blade.php)
-   **CSS Classes**: Tailwind utility classes
-   **Custom CSS**: kebab-case (juzoor-chart, root-card, subscription-widget)

## Controller Patterns

### Standard CRUD Actions

```php
index()    - Display listing
create()   - Show create form
store()    - Handle create submission
show()     - Display single item
edit()     - Show edit form
update()   - Handle edit submission
destroy()  - Delete item
```

### Quiz-Specific Actions

```php
take()              - Public quiz interface
submit()            - Process quiz answers
toggleStatus()      - Activate/deactivate quiz
duplicate()         - Copy quiz with questions
results()           - Redirect to quiz results
generateText()      - AI text generation (SUBSCRIPTION REQUIRED)
generateQuestions() - AI question generation (SUBSCRIPTION REQUIRED)
```

### NEW: Subscription Actions

```php
upgrade()               - Show subscription plans
createCheckout()        - Create Lemon Squeezy checkout
success()              - Payment success page
manage()               - Subscription management
webhook()              - Handle Lemon Squeezy webhooks
manageUserSubscription() - Admin: manage user subscription
updateUserSubscription() - Admin: update user subscription
```

### Authorization Pattern

```php
// Standard ownership check
if (!Auth::user()->is_admin && (int) $quiz->user_id !== Auth::id()) {
    abort(403, 'غير مصرح لك بهذا الإجراء.');
}

// Teacher/Admin check for quiz management
private function authorizeQuizManagement()
{
    if (!Auth::check() || (Auth::user()->user_type === 'student' && !Auth::user()->is_admin)) {
        abort(403, 'غير مصرح لك بإدارة الاختبارات. هذه الصفحة للمعلمين فقط.');
    }
}

// NEW: Subscription check for AI features
if (!Auth::user()->canUseAI()) {
    return response()->json([
        'message' => 'يتطلب هذا الاستخدام اشتراك نشط',
        'upgrade_required' => true
    ], 403);
}
```

## NEW: Subscription Patterns

### User Model Subscription Methods

```php
// Check subscription status
public function hasActiveSubscription(): bool
{
    return $this->subscription_active &&
           ($this->subscription_expires_at === null || $this->subscription_expires_at->isFuture());
}

// Check AI feature access
public function canUseAI(): bool
{
    return $this->hasActiveSubscription();
}

// Get current quota limits based on subscription
public function getCurrentQuotaLimits(): array
{
    if (!$this->hasActiveSubscription()) {
        return [
            'monthly_quiz_limit' => 5, // Free users
            'monthly_ai_text_limit' => 0,
            'monthly_ai_quiz_limit' => 0
        ];
    }

    $plan = $this->subscription?->subscriptionPlan;
    return [
        'monthly_quiz_limit' => $plan?->monthly_quiz_limit ?? 40,
        'monthly_ai_text_limit' => $plan?->monthly_ai_text_limit ?? 100,
        'monthly_ai_quiz_limit' => $plan?->monthly_ai_quiz_limit ?? 100
    ];
}

// Check monthly quota
public function hasReachedQuizLimit(): bool
{
    $quota = $this->monthlyQuota;
    $limits = $this->getCurrentQuotaLimits();

    return $quota && $quota->quiz_count >= $limits['monthly_quiz_limit'];
}
```

### Quota Tracking Pattern

```php
// Increment quota in controllers
public function store(Request $request)
{
    // Check quota before creation
    if (Auth::user()->hasReachedQuizLimit()) {
        $limits = Auth::user()->getCurrentQuotaLimits();
        return redirect()->back()->with('error',
            "لقد وصلت للحد الأقصى الشهري ({$limits['monthly_quiz_limit']} اختبار)"
        );
    }

    // Create quiz...

    // Increment quota counter
    $quota = MonthlyQuota::getOrCreateCurrent(Auth::id());
    $quota->incrementQuizCount();
}

// AI feature usage tracking
public function generateText(Request $request)
{
    // Check subscription
    if (!Auth::user()->canUseAI()) {
        return response()->json(['upgrade_required' => true], 403);
    }

    // Generate text...

    // Log usage
    $quota = MonthlyQuota::getOrCreateCurrent(Auth::id());
    $quota->incrementAiTextRequests();
}
```

### Lemon Squeezy Integration Pattern

```php
// Checkout creation
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
                'checkout_data' => [
                    'email' => $user->email,
                    'name' => $user->name,
                    'custom' => [
                        'user_id' => (string) $user->id,  // Important: Cast to string
                        'plan_id' => (string) $plan->id
                    ]
                ]
            ]
        ]
    ]);

    return $response->json('data.attributes.url');
}

// Webhook handling
public function handleWebhook(Request $request)
{
    // Verify signature
    if (!$this->verifyWebhookSignature($signature, $payload)) {
        return response('Unauthorized', 401);
    }

    // Process events
    $eventName = $data['meta']['event_name'];

    switch ($eventName) {
        case 'subscription_created':
            $this->handleSubscriptionCreated($data['data']);
            break;
        case 'subscription_updated':
            $this->handleSubscriptionUpdated($data['data']);
            break;
    }

    return response('OK', 200);
}
```

## Database Patterns

### Transaction Pattern

```php
DB::beginTransaction();
try {
    $quiz = Quiz::create($validated);
    $this->parseAndSaveQuestions($quiz, $aiResponse);

    // NEW: Increment quota
    $quota = MonthlyQuota::getOrCreateCurrent(Auth::id());
    $quota->incrementQuizCount();

    DB::commit();
    return redirect()->route('quizzes.show', $quiz);
} catch (\Exception $e) {
    DB::rollBack();
    Log::error('Quiz creation failed', ['error' => $e->getMessage()]);
    return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء الاختبار.');
}
```

### Subscription Data Pattern

```php
// Create subscription record
Subscription::create([
    'user_id' => $user->id,
    'lemon_squeezy_subscription_id' => 'admin_' . $user->id . '_' . time(),
    'lemon_squeezy_customer_id' => 'admin_customer_' . $user->id,
    'status' => 'active',
    'plan_name' => $plan->name,
    'plan_id' => $plan->id,
    'current_period_start' => now(),
    'current_period_end' => $validated['expires_at'],
]);

// Update user subscription status
$user->update([
    'subscription_active' => true,
    'subscription_expires_at' => $validated['expires_at'],
]);
```

### Root Score Initialization

```php
$rootScores = ['jawhar' => 0, 'zihn' => 0, 'waslat' => 0, 'roaya' => 0];
```

## AI Integration Patterns (UPDATED: June 2025)

### Conditional Quiz Generation - FIXED

**Issue Fixed**: Quiz generation was failing when no educational text was provided.

```php
// CORRECT: Conditional logic based on text_source
if ($request->text_source === 'none') {
    // Check subscription for no-text generation
    if (!Auth::user()->canUseAI()) {
        return response()->json([
            'message' => 'يتطلب إنشاء اختبار بدون نص اشتراك نشط',
            'upgrade_required' => true
        ], 403);
    }

    // Generate complete quiz with passage from scratch
    $aiResponse = $this->claudeService->generateJuzoorQuiz(
        $subjectName,
        $quiz->grade_level,
        $request->topic,
        $rootsForAI,
        true, // include passage
        $request->topic, // passage topic
        $totalRequested // total question count
    );
} else {
    // For manual text, check if AI generation is needed
    if ($request->text_source === 'manual' && !Auth::user()->canUseAI()) {
        // Redirect to manual question creation
        return response()->json([
            'success' => true,
            'message' => 'سيتم توجيهك لإضافة الأسئلة يدوياً',
            'redirect' => route('quizzes.questions.create', $quiz)
        ]);
    }

    // Generate questions from existing text
    $questions = $this->claudeService->generateQuestionsFromText(
        $educationalText,
        $subjectName,
        $quiz->grade_level,
        $rootsForAI
    );
}
```

### Validation Patterns - FIXED

**Issue Fixed**: Validation was requiring educational_text even when not needed.

```php
// CORRECT: Conditional validation
$validator = Validator::make($request->all(), [
    'topic' => 'required|string|max:255',
    'question_count' => 'required|integer|min:4|max:30',
    'educational_text' => 'nullable|required_unless:text_source,none|string|min:50',
    'text_source' => 'required|in:ai,manual,none',
    'roots' => 'required|array',
    'roots.*' => 'integer|min:0|max:20',
]);
```

## Frontend Patterns

### Subscription Gating in JavaScript

```javascript
// Handle subscription checks in quiz creation
function handleNoTextOption() {
    @if(!Auth::user()->canUseAI())
        // Show upgrade modal for non-subscribers
        showUpgradeModal();
    @else
        // Allow access for subscribers
        setTextSource('none');
    @endif
}

// Conditional text editor display
function setTextSource(source) {
    const aiOptions = document.getElementById('ai-text-options');
    const textEditor = document.getElementById('text-editor-container');

    if (source === 'ai') {
        aiOptions.classList.remove('hidden');
        @if(Auth::user()->canUseAI())
            textEditor.classList.remove('hidden');
        @else
            textEditor.classList.add('hidden'); // Hide for non-subscribers
        @endif
    }
}

// Step progression with subscription checks
function nextStep() {
    if (currentStep === 2) {
        @if(!Auth::user()->canUseAI())
            if (textSource === 'manual') {
                // Redirect to manual question creation
                setTimeout(() => {
                    window.location.href = '/quizzes/' + quizId + '/questions/create';
                }, 2000);
                return;
            }
        @endif
    }
    // Continue normal progression...
}
```

### Upgrade Prompts Pattern

```blade
<!-- Subscription status widget -->
@if(Auth::user()->hasActiveSubscription())
    <div class="bg-green-50 border border-green-200 rounded-xl p-6">
        <div class="flex items-center gap-3">
            <span class="text-2xl">💎</span>
            <div>
                <h3 class="font-bold text-green-900">اشتراك نشط</h3>
                <p class="text-green-700">يمكنك استخدام جميع مميزات الذكاء الاصطناعي</p>
            </div>
        </div>
    </div>
@else
    <div class="bg-purple-50 border border-purple-200 rounded-xl p-6">
        <div class="text-center">
            <span class="text-4xl mb-4">🤖</span>
            <h3 class="text-xl font-bold text-purple-900 mb-2">
                استخدم الذكاء الاصطناعي
            </h3>
            <a href="{{ route('subscription.upgrade') }}"
               class="bg-purple-600 text-white px-6 py-3 rounded-xl hover:bg-purple-700">
                اشترك الآن
            </a>
        </div>
    </div>
@endif
```

## ClaudeService Patterns (UPDATED: June 2025)

### Method Signatures - UPDATED

```php
// UPDATED: Added totalQuestions parameter
public function generateJuzoorQuiz(
    string $subject,
    int $gradeLevel,
    string $topic,
    array $roots,
    bool $includePassage = false,
    ?string $passageTopic = null,
    ?int $totalQuestions = null
): array

// UPDATED: Prompt building with question count
private function buildJuzoorQuizPrompt(
    string $subject,
    int $gradeLevel,
    string $topic,
    array $roots,
    bool $includePassage,
    ?string $passageTopic,
    int $totalQuestions
): string
```

### Fixed Prompt Pattern

```php
// FIXED: Variable conflict resolved
$prompt .= "\n\nتوزيع الأسئلة المطلوب (المجموع: {$totalQuestions} سؤال):";

foreach ($roots as $rootType => $levels) {
    $rootName = $this->getRootName($rootType);
    $prompt .= "\n\n{$rootName}:";
    foreach ($levels as $level => $count) {
        if ($count > 0) {
            $prompt .= "\n- المستوى {$level}: {$count} أسئلة";
        }
    }
}

$prompt .= "\n\n**تنبيه مهم: يجب أن يكون العدد الإجمالي للأسئلة هو {$totalQuestions} سؤال بالضبط.**";
```

## Recent Fixes Summary (June 2025)

### Major Updates

1. **Added Subscription System**: Complete Lemon Squeezy integration with monthly quotas
2. **Fixed Quiz Generation**: AI features properly gated behind subscriptions
3. **Enhanced User Management**: Admin subscription management interface
4. **Improved UX**: Clear upgrade prompts and subscription status indicators

### Key Patterns Added

1. **Subscription Checking**: `hasActiveSubscription()`, `canUseAI()` patterns
2. **Quota Management**: Monthly tracking and enforcement
3. **Payment Integration**: Lemon Squeezy checkout and webhook handling
4. **Feature Gating**: JavaScript and PHP subscription validation

---

**Always test subscription scenarios**:

-   ✅ Free users can create manual quizzes (limited quota)
-   ✅ Free users see upgrade prompts for AI features
-   ✅ Subscribers can use all AI features
-   ✅ Admin users have unlimited access
-   ✅ Quotas reset monthly and track accurately
