# Code Patterns & Conventions - جُذور (Juzoor)

Last Updated: June 17, 2025

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
take()              - Public quiz interface
submit()            - Process quiz answers
toggleStatus()      - Activate/deactivate quiz
duplicate()         - Copy quiz with questions
results()           - Redirect to quiz results
generateText()      - AI text generation
generateQuestions() - AI question generation (FIXED: June 2025)
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
$rootScores = ['jawhar' => 0, 'zihn' => 0, 'waslat' => 0, 'roaya' => 0];
```

## AI Integration Patterns (UPDATED: June 2025)

### Conditional Quiz Generation - FIXED

**Issue Fixed**: Quiz generation was failing when no educational text was provided.

```php
// CORRECT: Conditional logic based on text_source
if ($request->text_source === 'none') {
    // Generate complete quiz with passage from scratch
    Log::info('Generating complete quiz without existing text', [
        'quiz_id' => $quiz->id,
        'topic' => $request->topic
    ]);

    $aiResponse = $this->claudeService->generateJuzoorQuiz(
        $subjectName,
        $quiz->grade_level,
        $request->topic,
        $rootsForAI,
        true, // include passage
        $request->topic, // passage topic
        $totalRequested // total question count
    );

    $questions = $aiResponse['questions'] ?? [];
} else {
    // Generate questions from existing text
    Log::info('Generating questions from existing text', [
        'quiz_id' => $quiz->id,
        'text_length' => strlen($educationalText),
        'text_preview' => substr($educationalText, 0, 100)
    ]);

    $questions = $this->claudeService->generateQuestionsFromText(
        $educationalText,
        $subjectName,
        $quiz->grade_level,
        $rootsForAI
    );

    // Structure response for existing text
    $aiResponse = [
        'questions' => $questions,
        'passage' => $educationalText,
        'passage_title' => $request->topic
    ];
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
    // ... other validation rules
]);
```

### Roots Transformation - FIXED

**Issue Fixed**: Question count was not preserved due to improper use of `ceil()`.

```php
// CORRECT: Preserve exact question count
$rootsForAI = [];
$totalRequested = array_sum($request->roots);

foreach ($request->roots as $rootKey => $count) {
    if ($count > 0) {
        // Distribute more evenly and preserve exact count
        if ($count <= 2) {
            // For small counts, put everything in level 1
            $rootsForAI[$rootKey] = [
                '1' => $count,
                '2' => 0,
                '3' => 0
            ];
        } else {
            // For larger counts, distribute more carefully
            $level1 = floor($count * 0.4);
            $level2 = floor($count * 0.4);
            $level3 = $count - $level1 - $level2; // Remainder goes to level 3

            $rootsForAI[$rootKey] = [
                '1' => $level1,
                '2' => $level2,
                '3' => $level3
            ];
        }
    }
}

// Always log transformation for debugging
Log::info('Roots transformation', [
    'original_roots' => $request->roots,
    'transformed_roots' => $rootsForAI,
    'total_requested' => $totalRequested,
    'total_transformed' => array_sum(array_map(function($root) {
        return array_sum($root);
    }, $rootsForAI))
]);
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

$prompt .= "\n\n**تنبيه مهم: يجب أن يكون العدد الإجمالي للأسئلة هو {$totalQuestions} سؤال بالضبط. لا تولد أكثر أو أقل من هذا الرقم.**";
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

### Debugging Logs

```php
// Always log important operations
Log::info('Quiz generation attempt', [
    'user_id' => Auth::id(),
    'text_source' => $request->text_source,
    'topic' => $request->topic,
    'total_questions' => array_sum($request->roots)
]);

// Log transformations for debugging
Log::info('Roots transformation', [
    'original_roots' => $request->roots,
    'transformed_roots' => $rootsForAI,
    'total_requested' => $totalRequested,
    'total_transformed' => $transformedTotal
]);
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

## Guest Access Pattern

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

## Common Troubleshooting Patterns (NEW)

### Quiz Generation Issues

1. **Validation Errors**: Check that `educational_text` validation is conditional
2. **Question Count Mismatch**: Verify roots transformation preserves total count
3. **AI Service Errors**: Check ClaudeService logs and parameter passing
4. **Variable Conflicts**: Ensure no variable shadowing in prompt building

### Debugging Steps

```php
// 1. Check validation rules
'educational_text' => 'nullable|required_unless:text_source,none|string|min:50',

// 2. Verify roots transformation
Log::info('Roots transformation', [
    'total_requested' => $totalRequested,
    'total_transformed' => $transformedTotal
]);

// 3. Check AI service call
$aiResponse = $this->claudeService->generateJuzoorQuiz(
    $subjectName,
    $quiz->grade_level,
    $request->topic,
    $rootsForAI,
    true,
    $request->topic,
    $totalRequested // ← Ensure this is passed
);
```

## Recent Fixes Summary (June 2025)

1. **Fixed quiz generation without text**: Added conditional logic for `text_source === 'none'`
2. **Fixed validation rules**: Made `educational_text` conditional based on `text_source`
3. **Fixed question count preservation**: Improved roots transformation logic
4. **Fixed AI service parameters**: Added `totalQuestions` parameter to ensure exact count
5. **Fixed variable conflicts**: Resolved shadowing in prompt building

---

**Always test both scenarios**:

-   ✅ Quiz generation WITH educational text (`text_source: manual/ai`)
-   ✅ Quiz generation WITHOUT educational text (`text_source: none`)
