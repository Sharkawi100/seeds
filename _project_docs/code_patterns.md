# Code Patterns & Conventions - جُذور (Juzoor)
Last Updated: December 2024

## 📛 Naming Conventions

### PHP/Laravel
- **Models:** Singular PascalCase (User, Quiz, Question, Result, Answer)
- **Controllers:** PascalCase + Controller suffix
  - Web: QuizController, QuestionController, ResultController
  - Auth: AuthenticatedSessionController, RegisteredUserController
  - Admin: Admin\UserController, Admin\QuizController
- **Migrations:** timestamp_verb_description_table
  - `2025_05_27_165155_create_quizzes_table.php`
  - `2025_05_27_192054_add_passage_to_questions_table.php`
- **Database Tables:** Plural snake_case
  - users, quizzes, questions, results, answers, ai_usage_logs
- **Database Columns:** snake_case
  - user_id, quiz_id, root_type, depth_level, is_correct, guest_token
- **Routes:** kebab-case with dot notation for resources
  - `quizzes.index`, `quizzes.create`, `quiz.take`, `quiz.enter-pin`

### Juzoor-Specific Terms
- **Root Types:** Always lowercase English in code
  - `jawhar`, `zihn`, `waslat`, `roaya`
- **Arabic Display Names:**
  - جَوهر (Jawhar), ذِهن (Zihn), وَصلات (Waslat), رُؤية (Roaya)
- **Depth Levels:** Integers 1, 2, 3
- **Subjects:** `arabic`, `english`, `hebrew`

### Frontend
- **Blade Views:** kebab-case.blade.php
  - `quiz-results.blade.php`, `update-profile-information-form.blade.php`
- **Vue Components:** PascalCase (if used)
- **CSS Classes:** Tailwind utility classes
- **Custom CSS:** kebab-case (`juzoor-chart`, `root-card`)

## 🏗️ Common Patterns

### Controller Action Patterns
```php
// Standard CRUD
index()    - Display listing
create()   - Show create form
store()    - Handle create submission
show()     - Display single item
edit()     - Show edit form  
update()   - Handle edit submission
destroy()  - Delete item

// Quiz-specific
take()     - Public quiz taking interface
submit()   - Process quiz answers
generateText() - AI text generation
```

### Validation Patterns
```php
// Inline validation for simple cases
$validated = $request->validate([
    'title' => 'required|string|max:255',
    'subject' => 'required|in:arabic,english,hebrew',
    'grade_level' => 'required|integer|min:1|max:9',
]);

// Form Request for complex validation (LoginRequest)
public function rules(): array
{
    return [
        'email' => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
    ];
}

// Custom validation for quiz answers
'answers' => 'required|array',
'answers.*' => 'required|string'
```

### Database Transaction Pattern
```php
DB::beginTransaction();
try {
    $quiz = Quiz::create([...]);
    $this->parseAndSaveQuestions($quiz, $aiResponse);
    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
    Log::error('Quiz creation failed', ['error' => $e->getMessage()]);
}
```

### Authorization Patterns
```php
// Method 1: Direct check
if ((int) $quiz->user_id !== Auth::id()) {
    abort(403, 'غير مصرح لك بهذا الإجراء.');
}

// Method 2: Policy (QuizPolicy)
$this->authorize('view', $quiz);

// Method 3: Middleware
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin routes
});
```

## 🎯 Juzoor-Specific Patterns

### Root Score Calculation
```php
$rootScores = ['jawhar' => 0, 'zihn' => 0, 'waslat' => 0, 'roaya' => 0];
$rootCounts = ['jawhar' => 0, 'zihn' => 0, 'waslat' => 0, 'roaya' => 0];

// Always initialize all four roots
// Calculate percentages per root, not total
```

### Multi-language Patterns
```php
// Route definition
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'he', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// In Blade
{{ __('messages.welcome') }}
@lang('auth.failed')
```

### Guest Access Pattern
```php
// Always check both auth and guest token
if (Auth::check() && $result->user_id !== null) {
    return (int) $result->user_id === Auth::id();
}

if (!Auth::check() && $result->guest_token !== null) {
    return $result->guest_token === session('guest_token');
}
```

### AI Integration Pattern
```php
// Always wrap in try-catch
try {
    $response = $this->claudeService->generateJuzoorQuiz(...);
    // Process response
} catch (\Exception $e) {
    Log::error('AI generation failed', [
        'error' => $e->getMessage(),
        'quiz_id' => $quiz->id
    ]);
    // Handle gracefully
}
```

## 🔒 Security Patterns

### CSRF Protection
- Automatically applied to all POST/PUT/DELETE routes
- Use `@csrf` in all forms

### XSS Prevention
```blade
<!-- Always use double curly braces for output -->
{{ $user->name }}

<!-- Use {!! !!} only for trusted HTML -->
{!! $trustedHtml !!}
```

### Mass Assignment Protection
```php
// In Models
protected $fillable = ['title', 'subject', 'grade_level', 'settings'];
// Never include sensitive fields like is_admin
```

### Rate Limiting
```php
// Applied to auth routes
RateLimiter::hit($this->throttleKey());
// Max 5 attempts, then locked
```

## 📝 Documentation Standards

### Model Relationships
```php
/**
 * Get the questions for the quiz.
 */
public function questions(): HasMany
{
    return $this->hasMany(Question::class);
}
```

### Complex Methods
```php
/**
 * Parse and save questions from AI response
 * 
 * @param Quiz $quiz
 * @param array $aiResponse
 * @throws \Exception
 */
private function parseAndSaveQuestions(Quiz $quiz, array $aiResponse)
```

## 🎨 Frontend Patterns

### Tailwind Classes Organization
```blade
<!-- Order: Display, Position, Box Model, Typography, Visual, State -->
<div class="flex items-center justify-between p-6 text-lg font-bold bg-white rounded-lg shadow-md hover:shadow-lg transition-all">
```

### JavaScript in Blade
```blade
<!-- Inline scripts at bottom of view -->
@push('scripts')
<script>
    // Use async/await for AJAX
    async function generateQuestions() {
        const response = await fetch('{{ route("quizzes.generate-text") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
    }
</script>
@endpush
```

### Alpine.js Usage
```blade
<!-- For simple interactivity -->
<div x-data="{ open: false }">
    <button @click="open = !open">Toggle</button>
    <div x-show="open">Content</div>
</div>
```

## 🚀 Performance Patterns

### Eager Loading
```php
// Always eager load relationships
$quizzes = Quiz::with('questions')->get();
$quiz->load('questions', 'results');
```

### Caching
```php
// Cache expensive operations
$data = Cache::remember('welcome_page_data', 1800, function () {
    return [
        'stats' => $this->getGeneralStats(),
        'activeSubjects' => $this->getActiveSubjects()
    ];
});
```

### Pagination
```php
// Standard pagination
$users = User::paginate(20);

// With query string preservation
$users = User::paginate(20)->withQueryString();
```

## 🌍 API Response Pattern
```php
// Success response
return response()->json([
    'success' => true,
    'data' => $result,
    'message' => 'تم بنجاح'
]);

// Error response
return response()->json([
    'success' => false,
    'message' => 'فشل العملية: ' . $e->getMessage()
], 422);
```
