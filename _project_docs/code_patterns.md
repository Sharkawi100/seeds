# Code Patterns & Conventions - جُذور (Juzoor)

Last Updated: December 2024

## 📛 Naming Conventions

### PHP/Laravel

-   **Models:** Singular PascalCase (User, Quiz, Question, Result, Answer)
-   **Controllers:** PascalCase + Controller suffix
    -   Web: QuizController, QuestionController, ResultController
    -   Auth: AuthenticatedSessionController, RegisteredUserController
    -   Admin: Admin\UserController, Admin\QuizController
-   **Migrations:** timestamp_verb_description_table
    -   `2025_05_27_165155_create_quizzes_table.php`
    -   `2025_05_27_192054_add_passage_to_questions_table.php`
-   **Database Tables:** Plural snake_case
    -   users, quizzes, questions, results, answers, ai_usage_logs
-   **Database Columns:** snake_case
    -   user_id, quiz_id, root_type, depth_level, is_correct, guest_token
-   **Routes:** kebab-case with dot notation for resources
    -   `quizzes.index`, `quizzes.create`, `quiz.take`, `quiz.enter-pin`

### Juzoor-Specific Terms

-   **Root Types:** Always lowercase English in code
    -   `jawhar`, `zihn`, `waslat`, `roaya`
-   **Arabic Display Names:**
    -   جَوهر (Jawhar), ذِهن (Zihn), وَصلات (Waslat), رُؤية (Roaya)
-   **Depth Levels:** Integers 1, 2, 3
-   **Content Subjects:** Used for AI content generation
    -   `arabic`, `english`, `hebrew` (for generating educational content)

### Frontend

-   **Blade Views:** kebab-case.blade.php
    -   `quiz-results.blade.php`, `update-profile-information-form.blade.php`
-   **Vue Components:** PascalCase (if used)
-   **CSS Classes:** Tailwind utility classes
-   **Custom CSS:** kebab-case (`juzoor-chart`, `root-card`)

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
    'subject_id' => 'required|exists:subjects,id',
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

### Language Support

```php
// Interface Language: Arabic Only
// The entire website interface is in Arabic
// All UI text, labels, messages, and navigation are in Arabic

// Content Generation: Multi-language Support
// AI content generation supports multiple languages for educational content:
$this->claudeService->generateEducationalText(
    'arabic',    // Educational content in Arabic
    'english',   // Educational content in English
    'hebrew',    // Educational content in Hebrew
    $gradeLevel,
    $topic,
    $textType,
    $length
);

// Static UI Text (always Arabic)
'غير مصرح لك بهذا الإجراء.'
'تم إنشاء الاختبار بنجاح.'
'يرجى ملء جميع الحقول المطلوبة'

// No translation files needed - interface is Arabic-only
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

-   Automatically applied to all POST/PUT/DELETE routes
-   Use `@csrf` in all forms

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
protected $fillable = ['title', 'subject_id', 'grade_level', 'settings'];
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

### RTL Layout Support

```blade
<!-- Built-in RTL support for Arabic interface -->
<div class="flex items-center space-x-3 rtl:space-x-reverse">
    <span>النص العربي</span>
    <svg class="w-5 h-5">...</svg>
</div>

<!-- CSS utilities for RTL -->
.rtl\:space-x-reverse > :not([hidden]) ~ :not([hidden]) {
    --tw-space-x-reverse: 1;
}
```

## 🚀 Performance Patterns

### Eager Loading

```php
// Always eager load relationships
$quizzes = Quiz::with('questions', 'subject')->get();
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

## 🌍 Localization Pattern

### Arabic-Only Interface

```php
// No localization files needed - everything is in Arabic
// All interface text is directly written in Arabic

// Blade template example
<h1 class="text-2xl font-bold">إنشاء اختبار جديد</h1>
<p class="text-gray-600">ابدأ رحلة تعليمية جديدة مع نموذج جُذور</p>

// Success/Error messages
return redirect()->back()->with('success', 'تم حفظ البيانات بنجاح');
return redirect()->back()->with('error', 'حدث خطأ أثناء العملية');

// Validation messages
$validator->errors()->add('field', 'هذا الحقل مطلوب');
```

### Content Language Configuration

```php
// Only for AI-generated educational content (not interface)
// These are content languages for educational material generation
$supportedContentLanguages = ['arabic', 'english', 'hebrew'];

// Subject configuration for content generation
$subjects = [
    'اللغة العربية' => 'arabic',      // Arabic language content
    'اللغة الإنجليزية' => 'english',    // English language content
    'اللغة العبرية' => 'hebrew',       // Hebrew language content
    'الرياضيات' => 'arabic',          // Math content in Arabic
    'العلوم' => 'arabic',             // Science content in Arabic
];
```

## 🌟 API Response Pattern

```php
// All responses in Arabic
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

## 📱 Mobile & Responsive Patterns

```blade
<!-- Mobile-first approach with Arabic RTL support -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <div class="p-4 bg-white rounded-lg shadow text-right">
        <!-- Arabic text naturally aligns right -->
        <h3 class="text-lg font-bold">عنوان القسم</h3>
        <p class="text-gray-600">وصف المحتوى</p>
    </div>
</div>
```

## 🎯 Quiz Configuration Patterns

```php
// New quiz configuration settings
$quizSettings = [
    'time_limit' => $request->time_limit,           // null or minutes (5-180)
    'passing_score' => $request->passing_score,     // 50-90%
    'shuffle_questions' => $request->shuffle_questions, // boolean
    'shuffle_answers' => $request->shuffle_answers,     // boolean
    'show_results' => $request->show_results,           // boolean
    'is_active' => $request->activate_quiz,             // boolean
];

// Apply settings to quiz
$quiz->update($quizSettings);
```
