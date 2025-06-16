# Code Patterns & Conventions - جُذور (Juzoor)

Last Updated: June 16, 2025

## Naming Conventions

### PHP/Laravel

-   **Models**: Singular PascalCase (User, Quiz, Question, Result, Answer)
-   **Controllers**: PascalCase + Controller suffix
    -   Web: QuizController, QuestionController, ResultController
    -   Auth: AuthenticatedSessionController, RegisteredUserController
    -   Admin: Admin\UserController, Admin\QuizController
-   **Database Tables**: Plural snake_case (users, quizzes, questions, results, answers)
-   **Database Columns**: snake_case (user_id, quiz_id, root_type, depth_level, guest_token)
-   **Routes**: kebab-case with dot notation (quizzes.index, quiz.take, quiz.enter-pin)

### Juzoor-Specific Terms

-   **Root Types**: Always lowercase English in code: `jawhar`, `zihn`, `waslat`, `roaya`
-   **Arabic Display**: جَوهر (Jawhar), ذِهن (Zihn), وَصلات (Waslat), رُؤية (Roaya)
-   **Depth Levels**: Integers 1, 2, 3
-   **Subjects**: Database uses subject_id (foreign key to subjects table)

### Frontend

-   **Blade Views**: kebab-case.blade.php (quiz-results.blade.php, guest-info.blade.php)
-   **CSS Classes**: Tailwind utility classes
-   **Custom CSS**: kebab-case (juzoor-chart, root-card)

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
take()         - Public quiz interface
submit()       - Process quiz answers
toggleStatus() - Activate/deactivate quiz
duplicate()    - Copy quiz with questions
results()      - Redirect to quiz results
generateText() - AI text generation
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
```

## Database Patterns

### Transaction Pattern

```php
DB::beginTransaction();
try {
    $quiz = Quiz::create($validated);
    $this->parseAndSaveQuestions($quiz, $aiResponse);
    DB::commit();
    return redirect()->route('quizzes.show', $quiz);
} catch (\Exception $e) {
    DB::rollBack();
    Log::error('Quiz creation failed', ['error' => $e->getMessage()]);
    return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء الاختبار.');
}
```

### Root Score Initialization

```php
// Always initialize all four roots
$rootScores = ['jawhar' => 0, 'zihn' => 0, 'waslat' => 0, 'roaya' => 0];
$rootCounts = ['jawhar' => 0, 'zihn' => 0, 'waslat' => 0, 'roaya' => 0];
```

### Relationship Loading

```php
// Always eager load required relationships
$quiz = Quiz::with(['questions', 'subject', 'results'])->find($id);
$results = Result::with(['quiz.subject', 'user'])->where('quiz_id', $quizId)->get();
```

## Validation Patterns

### Standard Validation

```php
$validated = $request->validate([
    'title' => 'required|string|max:255',
    'subject_id' => 'required|exists:subjects,id',
    'grade_level' => 'required|integer|min:1|max:9',
    'description' => 'nullable|string',
]);
```

### Arabic Text Validation

```php
'title' => 'required|string|max:255|regex:/^[\p{Arabic}\s\p{P}\p{N}]+$/u',
'passage' => 'nullable|string|regex:/^[\p{Arabic}\s\p{P}\p{N}\p{L}]+$/u',
```

## Guest Access Patterns

### Guest Token Management

```php
// Generate guest token for results
$guestToken = Str::random(32);
$result = Result::create([
    'quiz_id' => $quiz->id,
    'user_id' => null,
    'guest_token' => $guestToken,
    'guest_name' => session('guest_name'),
    'expires_at' => now()->addDays(7),
]);
```

### Guest Authorization Check

```php
// Check guest access to results
if (Auth::check() && $result->user_id !== null) {
    return (int) $result->user_id === Auth::id();
}

if (!Auth::check() && $result->guest_token !== null) {
    return $result->guest_token === $token;
}

return false;
```

### Session Management

```php
// Store guest info during quiz
session(['guest_name' => $validated['guest_name']]);
session(['school_class' => $validated['school_class']]);

// Clear after submission
session()->forget(['guest_name', 'school_class']);
```

## Data Handling Patterns

### JSON Score Processing

```php
// Handle both array and JSON string formats
$scores = is_array($result->scores)
    ? $result->scores
    : json_decode($result->scores ?? '{}', true);
```

### Laravel Collection to JavaScript

```php
// Convert for Chart.js
const results = @json($results->values()); // Converts collection to array

// JavaScript processing
results.forEach(result => {
    const scores = typeof result.scores === 'string'
        ? JSON.parse(result.scores)
        : result.scores;
});
```

## UI Patterns

### Modern Glassmorphism Cards

```blade
<div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl transition-all duration-300">
    <!-- Card content -->
</div>
```

### Root Performance Display

```blade
@php
$roots = [
    'jawhar' => ['name' => 'جَوهر', 'icon' => '🎯', 'color' => 'blue'],
    'zihn' => ['name' => 'ذِهن', 'icon' => '🧠', 'color' => 'purple'],
    'waslat' => ['name' => 'وَصلات', 'icon' => '🔗', 'color' => 'green'],
    'roaya' => ['name' => 'رُؤية', 'icon' => '👁️', 'color' => 'orange']
];
$scores = is_array($result->scores) ? $result->scores : json_decode($result->scores ?? '{}', true);
@endphp

@foreach($roots as $key => $root)
<div class="text-center">
    <div class="text-2xl mb-1">{{ $root['icon'] }}</div>
    <div class="text-sm font-bold text-{{ $root['color'] }}-600">
        {{ $scores[$key] ?? 0 }}%
    </div>
</div>
@endforeach
```

### Conditional Management Buttons

```blade
@if(!$quiz->has_submissions)
    <a href="{{ route('quizzes.edit', $quiz) }}" class="btn-primary">
        <i class="fas fa-edit"></i>
        تعديل الاختبار
    </a>
@else
    <form action="{{ route('quizzes.toggle-status', $quiz) }}" method="POST" class="inline">
        @csrf
        @method('PATCH')
        <button type="submit" class="btn-{{ $quiz->is_active ? 'danger' : 'success' }}">
            {{ $quiz->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}
        </button>
    </form>
@endif
```

## Chart.js Integration Patterns

### Chart Initialization

```javascript
// Check element exists before creating chart
const canvas = document.getElementById("rootsRadarChart");
if (canvas) {
    const ctx = canvas.getContext("2d");
    new Chart(ctx, {
        type: "radar",
        data: {
            labels: [
                "جَوهر (الماهية)",
                "ذِهن (التحليل)",
                "وَصلات (الربط)",
                "رُؤية (التطبيق)",
            ],
            datasets: [
                {
                    data: [
                        rootAverages.jawhar,
                        rootAverages.zihn,
                        rootAverages.waslat,
                        rootAverages.roaya,
                    ],
                    backgroundColor: "rgba(99, 102, 241, 0.2)",
                    borderColor: "rgba(99, 102, 241, 1)",
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    pointLabels: {
                        font: { family: "Tajawal" },
                    },
                },
            },
        },
    });
}
```

### Data Processing for Charts

```javascript
// Calculate root averages
const rootAverages = { jawhar: 0, zihn: 0, waslat: 0, roaya: 0 };
let validResults = 0;

results.forEach((result) => {
    if (result.scores) {
        const scores =
            typeof result.scores === "string"
                ? JSON.parse(result.scores)
                : result.scores;
        rootAverages.jawhar += scores.jawhar || 0;
        rootAverages.zihn += scores.zihn || 0;
        rootAverages.waslat += scores.waslat || 0;
        rootAverages.roaya += scores.roaya || 0;
        validResults++;
    }
});

// Calculate averages
Object.keys(rootAverages).forEach((key) => {
    rootAverages[key] = validResults > 0 ? rootAverages[key] / validResults : 0;
});
```

## TinyMCE Integration Pattern

### Editor Initialization

```javascript
tinymce.init({
    selector: ".tinymce-editor",
    language: "ar",
    directionality: "rtl",
    height: 350,
    menubar: false,
    plugins:
        "lists link charmap preview searchreplace autolink directionality code",
    toolbar:
        "undo redo | bold italic underline | bullist numlist | link | removeformat | code",
    content_style:
        'body { font-family: "Tajawal"; font-size: 16px; direction: rtl; }',
    branding: false,
    entity_encoding: "raw",
});

// Ensure content saves on form submission
document.querySelector("form").addEventListener("submit", function (e) {
    tinymce.triggerSave();
});
```

### Content Display

```blade
<!-- Use {!! !!} for TinyMCE content to preserve formatting -->
{!! $quiz->questions->first()->passage !!}
```

## AI Integration Patterns

### Content Generation

```php
try {
    $text = $this->claudeService->generateEducationalText(
        $validated['subject'],
        $validated['grade_level'],
        $validated['topic'],
        $validated['text_type'],
        $validated['length']
    );

    return response()->json(['success' => true, 'text' => $text]);
} catch (\Exception $e) {
    Log::error('Text generation failed', [
        'error' => $e->getMessage(),
        'params' => $validated
    ]);

    return response()->json([
        'success' => false,
        'message' => 'فشل توليد النص: ' . $e->getMessage()
    ], 422);
}
```

## Security Patterns

### CSRF Protection

```blade
<!-- Always include CSRF in forms -->
<form action="{{ route('quizzes.store') }}" method="POST">
    @csrf
    <!-- form fields -->
</form>

<!-- AJAX requests -->
fetch('{{ route("quizzes.generate-text") }}', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Content-Type': 'application/json',
    },
    body: JSON.stringify(data)
});
```

### XSS Prevention

```blade
<!-- Escape output by default -->
{{ $user->name }}

<!-- Only use raw output for trusted HTML -->
{!! $quiz->passage !!}
```

## Error Handling Patterns

### User-Friendly Messages

```php
// Arabic error messages
return redirect()->back()->with('error', 'حدث خطأ أثناء العملية. الرجاء المحاولة مرة أخرى.');
return redirect()->route('quizzes.index')->with('success', 'تم حفظ التغييرات بنجاح.');

// API responses
return response()->json([
    'success' => false,
    'message' => 'فشل العملية: ' . $e->getMessage()
], 422);
```

### Logging Pattern

```php
Log::error('Operation failed', [
    'error' => $e->getMessage(),
    'user_id' => Auth::id(),
    'quiz_id' => $quiz->id ?? null,
    'trace' => $e->getTraceAsString()
]);
```

## Performance Patterns

### Eager Loading

```php
// Load required relationships upfront
$quizzes = Quiz::with(['questions', 'subject', 'results'])->where('user_id', Auth::id())->get();
```

### Pagination

```php
// Standard pagination with query preservation
$results = Result::with(['quiz', 'user'])->paginate(20)->withQueryString();
```

### Chart Performance

```javascript
// Only create charts when elements exist
document.addEventListener("DOMContentLoaded", function () {
    const elements = ["rootsRadarChart", "scoreDistributionChart"];
    elements.forEach((elementId) => {
        const canvas = document.getElementById(elementId);
        if (canvas) {
            createChart(canvas, elementId);
        }
    });
});
```

## Mobile & RTL Patterns

### Responsive Design

```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <div class="p-4 bg-white rounded-lg text-right">
        <h3 class="text-lg font-bold">{{ $title }}</h3>
    </div>
</div>
```

### RTL Layout

```css
/* Automatic RTL support with Tailwind */
.space-x-3 {
    /* Automatically becomes space-x-reverse in RTL */
}
.text-left {
    /* Becomes text-right in RTL */
}
.mr-4 {
    /* Becomes ml-4 in RTL */
}
```

## Route Organization Pattern

### Grouped Routes

```php
// Public routes
Route::prefix('quiz')->name('quiz.')->group(function () {
    Route::post('/enter-pin', [WelcomeController::class, 'enterPin'])->name('enter-pin');
    Route::get('/{quiz}/take', [QuizController::class, 'take'])->name('take');
});

// Authenticated routes with middleware
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('quizzes', QuizController::class);

    Route::prefix('quizzes')->name('quizzes.')->group(function () {
        Route::patch('/{quiz}/toggle-status', [QuizController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{quiz}/duplicate', [QuizController::class, 'duplicate'])->name('duplicate');
    });
});
```

## Data Export Patterns

### Collection Transformation

```php
// Transform for frontend consumption
$transformedResults = $results->map(function ($result) {
    return [
        'id' => $result->id,
        'student_name' => $result->guest_name ?: ($result->user?->name ?? 'غير معروف'),
        'total_score' => $result->total_score,
        'scores' => is_array($result->scores) ? $result->scores : json_decode($result->scores ?? '{}', true),
        'created_at' => $result->created_at->format('Y-m-d H:i')
    ];
});
```
