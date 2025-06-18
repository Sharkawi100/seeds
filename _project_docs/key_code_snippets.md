# Key Code Snippets - جُذور (Juzoor)

Last Updated: December 2024

## 🔐 Authentication Patterns

### Login Implementation

```php
// AuthenticatedSessionController.php
public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();
    return redirect()->intended(route('dashboard', absolute: false));
}

// LoginRequest.php - with rate limiting
public function authenticate(): void
{
    $this->ensureIsNotRateLimited();

    if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
        RateLimiter::hit($this->throttleKey());
        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    RateLimiter::clear($this->throttleKey());
}
```

### Registration Pattern

```php
// RegisteredUserController.php
public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    event(new Registered($user));
    Auth::login($user);

    return redirect(route('dashboard', absolute: false));
}
```

## 📝 Quiz Creation Patterns

### AI-Powered Quiz Generation

```php
// QuizController.php
if ($validated['creation_method'] === 'ai') {
    $aiResponse = $this->claudeService->generateJuzoorQuiz(
        $quiz->subject,
        $quiz->grade_level,
        $validated['topic'],
        $settingsForClaude,
        true, // include passage
        $validated['passage_topic'] ?? null
    );

    $this->parseAndSaveQuestions($quiz, $aiResponse);
}
```

### Question Storage Pattern

```php
Question::create([
    'quiz_id' => $quiz->id,
    'question' => $questionData['question'],
    'root_type' => $questionData['root_type'], // jawhar|zihn|waslat|roaya
    'depth_level' => (int) $questionData['depth_level'], // 1|2|3
    'options' => $options,
    'correct_answer' => $questionData['correct_answer'],
    'passage' => $index === 0 ? $passage : null,
    'passage_title' => $index === 0 ? $passageTitle : null,
]);
```

## 🎯 Guest Access Pattern

### PIN-Based Quiz Access

```php
// WelcomeController.php
public function enterPin(Request $request)
{
    $validated = $request->validate([
        'pin' => 'required|string|size:6'
    ]);

    $quiz = Quiz::where('pin', $validated['pin'])
        ->where('is_active', true)
        ->first();

    if (!$quiz) {
        return redirect()->back()
            ->with('error', 'رمز الاختبار غير صحيح أو منتهي الصلاحية');
    }

    return redirect()->route('quiz.take', $quiz);
}
```

### Guest Result Storage

```php
// QuizController.php - submit method
if (!Auth::check()) {
    $result->guest_token = Str::random(32);
    session(['guest_token' => $result->guest_token]);
}
```

## 📊 Results Calculation Pattern

### Root-wise Scoring

```php
// Calculate scores per root
$rootScores = ['jawhar' => 0, 'zihn' => 0, 'waslat' => 0, 'roaya' => 0];
$rootCounts = ['jawhar' => 0, 'zihn' => 0, 'waslat' => 0, 'roaya' => 0];

foreach ($validated['answers'] as $questionId => $selectedAnswer) {
    $question = $quiz->questions->find($questionId);
    if (!$question) continue;

    $isCorrect = $question->correct_answer === $selectedAnswer;

    Answer::create([
        'question_id' => $questionId,
        'result_id' => $result->id,
        'selected_answer' => $selectedAnswer,
        'is_correct' => $isCorrect
    ]);

    $rootCounts[$question->root_type]++;
    if ($isCorrect) {
        $rootScores[$question->root_type]++;
    }
}

// Calculate percentages
foreach ($rootScores as $root => $score) {
    if ($rootCounts[$root] > 0) {
        $rootScores[$root] = round(($score / $rootCounts[$root]) * 100);
    }
}
```

## 🤖 AI Integration Patterns

### Claude Service Usage

```php
// Generate educational text
$text = $this->claudeService->generateEducationalText(
    $validated['subject'],      // arabic|english|hebrew
    $validated['grade_level'],  // 1-9
    $validated['topic'],
    $validated['text_type'],    // story|article|dialogue|description
    $validated['length']        // short|medium|long
);

// Generate questions from text
$questions = $this->claudeService->generateQuestionsFromText(
    $validated['passage'],
    $validated['subject'],
    $validated['grade_level'],
    $validated['roots']  // ['jawhar' => ['1' => 2, '2' => 3], ...]
);
```

## 🔒 Authorization Patterns

### Policy-based Authorization

```php
// QuizPolicy.php
public function view(User $user, Quiz $quiz): bool
{
    return $user->id === $quiz->user_id;
}

// In Controllers
if ((int) $quiz->user_id !== Auth::id()) {
    abort(403, 'غير مصرح لك بهذا الإجراء.');
}
```

### Admin Middleware

```php
// IsAdmin.php
public function handle(Request $request, Closure $next): Response
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    if (!Auth::user()->is_admin) {
        abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
    }

    return $next($request);
}
```

## 🌐 Localization Pattern

### Multi-language Support

```php
// SetLocale Middleware
public function handle(Request $request, Closure $next)
{
    if (session()->has('locale')) {
        app()->setLocale(session('locale'));
    }
    return $next($request);
}

// Language Switching Route
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'he', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');
```

## 📧 Email Pattern

### Contact Form Handling

```php
Mail::to(config('mail.admin_email', 'admin@iseraj.com'))
    ->send(new ContactInquiry($validated));
```

## 🎨 Blade Component Usage

### Custom Juzoor Chart Component

````blade
<x-juzoor-chart :scores="$result->scores" size="medium" />

<!-- Component accepts scores array -->
@props(['scores' => null, 'size' => 'medium'])
@php
$defaultScores = $scores ?? [
    'jawhar' => 0,
    'zihn' => 0,
    'waslat' => 0,
    'roaya' => 0
];
@endphp
```## 🔐 Middleware Registration Pattern

### Middleware Aliases (Laravel 11)
```php
// bootstrap/app.php
->withMiddleware(function (Middleware $middleware) {
    // Register custom middleware aliases
    $middleware->alias([
        'admin' => IsAdmin::class,
        'teacher' => CanCreateQuizzes::class,
        'active' => CheckUserActive::class,
    ]);

    // Web middleware stack
    $middleware->web(append: [
        \App\Http\Middleware\SetLocale::class,
    ]);
})
````
