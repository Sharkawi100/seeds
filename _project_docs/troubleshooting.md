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

# Troubleshooting Guide - جُذور Platform

Last Updated: June 28, 2025

---

## 🔥 Critical Issues & Quick Fixes

### 1. Passage Editing Not Saving (Form Submission Issues)

**Symptoms:**

-   Teacher edits passage text with formatting
-   Form appears to submit successfully
-   Changes don't persist after page refresh
-   No error messages displayed

**Quick Diagnosis:**

```sql
-- Check if passage data exists
SELECT id, quiz_id, passage, passage_title
FROM questions
WHERE quiz_id = YOUR_QUIZ_ID
ORDER BY id LIMIT 1;
```

**Root Causes & Solutions:**

#### A) Validation Rule Mismatch (Most Common)

```php
// ❌ PROBLEM: Form sends 'subject' but validation expects 'subject_id'
<input type="hidden" name="subject" value="{{ $quiz->subject }}">

// ✅ SOLUTION: Match form fields to validation rules
<input type="hidden" name="subject_id" value="{{ $quiz->subject_id }}">
```

#### B) Missing Validation Fields

```php
// ❌ PROBLEM: Missing passage fields in validation
$validated = $request->validate([
    'title' => 'required|string|max:255',
    'subject_id' => 'required|exists:subjects,id',
    // Missing: passage fields!
]);

// ✅ SOLUTION: Add passage validation
$validated = $request->validate([
    'title' => 'required|string|max:255',
    'subject_id' => 'required|exists:subjects,id',
    'passage' => 'nullable|string',
    'passage_title' => 'nullable|string|max:255',
]);
```

#### C) Controller Route Confusion

-   **Individual Question Edit**: `QuestionController@update` - needs passage handling
-   **Questions Index Edit**: `QuizController@update` - already has passage handling
-   **Solution**: Ensure both controllers handle passage data properly

**Debugging Steps:**

1. Check browser Network tab for failed validation
2. Look for 422 validation errors in response
3. Compare form field names with controller validation rules
4. Test both editing methods (individual vs. bulk)

---

### 2. Quiz Access Issues (404 vs. Inactive)

**Symptoms:**

-   Students get 404 error when accessing quiz
-   Quiz appears active in teacher dashboard
-   URL is correct but shows "Not Found"

**Quick Diagnosis:**

```sql
-- Check quiz status
SELECT id, title, is_active, expires_at, pin
FROM quizzes
WHERE id = YOUR_QUIZ_ID;
```

**Solutions:**

#### A) Quiz Deactivated (Most Common)

```php
// ❌ PROBLEM: Using abort(404) for inactive quizzes
if (!$quiz->is_active) {
    abort(404, 'Quiz not available');
}

// ✅ SOLUTION: User-friendly message
if (!$quiz->is_active) {
    return view('quiz.inactive', [
        'quiz' => $quiz,
        'message' => 'هذا الاختبار غير مفعل حالياً',
        'description' => 'يرجى التواصل مع معلمك...'
    ]);
}
```

#### B) Quiz Expired

```php
// Check expiration
if ($quiz->expires_at && $quiz->expires_at->isPast()) {
    return view('quiz.expired', compact('quiz'));
}
```

---

## 🔧 Common Development Issues

### 3. AI Quiz Generation Failures

**Symptoms:**

-   422 validation errors during quiz creation
-   "Educational text required" for text_source: 'none'
-   Generated question count doesn't match requested

**Root Causes & Solutions:**

#### A) Text Source Validation Issues

```php
// ❌ PROBLEM: Always requiring educational_text
'educational_text' => 'required|string|min:50',

// ✅ SOLUTION: Conditional validation
'educational_text' => 'nullable|required_unless:text_source,none|string|min:50',
```

#### B) Question Count Mismatch

```php
// ❌ PROBLEM: Variable shadowing in prompt building
foreach ($roots as $root) {
    $totalQuestions += ...; // Overwrites parameter!
}

// ✅ SOLUTION: Use different variable names
foreach ($roots as $root) {
    $questionCount += ...;
}
```

**Debugging Steps:**

```php
// 1. Check validation rules
Log::info('Validation data:', $request->all());

// 2. Verify roots transformation
Log::info('Roots transformation:', [
    'total_requested' => $totalRequested,
    'total_transformed' => $transformedTotal
]);

// 3. Check AI service parameters
Log::info('AI service call:', [
    'total_questions_param' => $totalRequested
]);
```

---

### 4. Form Validation & Data Issues

**Common Form Problems:**

#### A) Missing CSRF Token

```blade
{{-- ❌ PROBLEM: Missing CSRF --}}
<form method="POST">

{{-- ✅ SOLUTION: Always include CSRF --}}
<form method="POST">
    @csrf
    @method('PUT')
</form>
```

#### B) Incorrect Method Spoofing

```blade
{{-- ❌ PROBLEM: Wrong HTTP method --}}
<form method="POST" action="{{ route('quiz.update', $quiz) }}">

{{-- ✅ SOLUTION: Use method spoofing --}}
<form method="POST" action="{{ route('quiz.update', $quiz) }}">
    @method('PUT')
</form>
```

#### C) JavaScript Form Handling

```javascript
// ❌ PROBLEM: TinyMCE content not submitted
form.submit();

// ✅ SOLUTION: Trigger TinyMCE save first
tinymce.triggerSave();
form.submit();
```

---

## 📊 Database Issues

### 5. Database Connection & Query Problems

**Connection Issues:**

```bash
# Check database connection
php artisan tinker
DB::connection()->getPdo();
```

**Query Debugging:**

```php
// Enable query logging
DB::enableQueryLog();
// Your code here
dd(DB::getQueryLog());
```

**Common SQL Issues:**

```sql
-- Check table structure
DESCRIBE questions;
DESCRIBE quizzes;

-- Check data integrity
SELECT COUNT(*) FROM questions WHERE quiz_id = ?;
SELECT COUNT(*) FROM quizzes WHERE user_id = ?;
```

---

## 🎯 Performance Issues

### 6. Slow Page Load & Query Optimization

**N+1 Query Problem:**

```php
// ❌ PROBLEM: N+1 queries
foreach ($quizzes as $quiz) {
    echo $quiz->questions->count(); // Separate query each time
}

// ✅ SOLUTION: Eager loading
$quizzes = Quiz::with('questions')->get();
```

**Memory Issues:**

```php
// ❌ PROBLEM: Loading all data at once
$results = Result::all();

// ✅ SOLUTION: Use pagination or chunking
$results = Result::paginate(50);
// or
Result::chunk(100, function($results) {
    // Process batch
});
```

---

## 🔍 Debugging Tools & Commands

### Essential Laravel Commands

```bash
# Clear all caches
php artisan optimize:clear

# Check route list
php artisan route:list | grep quiz

# View logs in real-time
tail -f storage/logs/laravel.log

# Database migrations status
php artisan migrate:status

# Test queue jobs
php artisan queue:work --once
```

### Browser Debugging

```javascript
// Check for JavaScript errors
console.log("Form data:", new FormData(form));

// Check AJAX responses
fetch("/api/endpoint")
    .then((r) => r.json())
    .then(console.log);

// TinyMCE debugging
tinymce.get("editor-id").getContent();
```

### SQL Debugging Queries

```sql
-- Check for recent errors
SELECT * FROM failed_jobs ORDER BY failed_at DESC LIMIT 5;

-- Analyze quiz usage
SELECT
    u.name,
    COUNT(q.id) as quiz_count,
    COUNT(r.id) as result_count
FROM users u
LEFT JOIN quizzes q ON u.id = q.user_id
LEFT JOIN results r ON q.id = r.quiz_id
GROUP BY u.id;

-- Find problematic quizzes
SELECT q.id, q.title, COUNT(questions.id) as question_count
FROM quizzes q
LEFT JOIN questions ON q.id = questions.quiz_id
GROUP BY q.id
HAVING question_count = 0;
```

---

## 🚨 Emergency Fixes

### Quick Hotfixes for Production

#### 1. Disable Problematic Feature

```php
// In controller method
if (config('app.env') === 'production') {
    return redirect()->back()->with('info', 'Feature temporarily disabled');
}
```

#### 2. Bypass Validation Temporarily

```php
// Add to validation rules as fallback
'field_name' => 'sometimes|nullable|string',
```

#### 3. Database Rollback

```bash
# Rollback last migration
php artisan migrate:rollback --step=1

# Check migration status
php artisan migrate:status
```

---

## 📞 Getting Help

### When to Escalate

1. **Data Loss Issues**: Any reports of lost quiz content
2. **Authentication Problems**: Users can't log in or access their content
3. **Payment Issues**: Subscription or billing problems
4. **Performance Issues**: Site loading slower than 3 seconds
5. **Security Concerns**: Any suspicious activity or potential vulnerabilities

### Information to Collect

1. **User Information**: User ID, email, role
2. **Error Details**: Full error message, stack trace
3. **Browser Information**: Version, console errors
4. **Steps to Reproduce**: Exact sequence that caused the issue
5. **Environment**: Production vs. staging vs. development

### Log Files to Check

-   `storage/logs/laravel.log` - Application errors
-   Web server error logs - Server-level issues
-   Database slow query logs - Performance issues
-   Browser console - Frontend errors

---

## ✅ Recent Fixes Summary (June 2025)

1. ✅ **Passage editing validation mismatch** - Fixed form field naming
2. ✅ **Inactive quiz UX** - Replaced 404 with user-friendly message
3. ✅ **Quiz generation without text** - Fixed conditional validation logic
4. ✅ **Question count preservation** - Resolved variable shadowing
5. ✅ **TinyMCE content saving** - Enhanced form submission handling

**Always test both scenarios when applicable:**

-   With/without educational text for quiz generation
-   Individual vs. bulk question editing
-   Authenticated vs. guest user access
-   Different user roles (teacher vs. student vs. admin)

````markdown
## AI Reports System (NEW - June 2025)

### Common Issues

#### Report Generation Fails

```php
// Check subscription status
if (!Auth::user()->canUseAI()) {
    // User needs active subscription
}

// Check quota
$quota = MonthlyQuota::getOrCreateCurrent(Auth::id());
if (!$quota->hasRemainingAiReportQuota()) {
    // User exceeded monthly quota
}

// Check minimum results
if ($results->count() < 3) {
    // Need at least 3 student results
}

// AI generation failure handling
try {
    $aiReport = $claudeService->generatePedagogicalReport(...);
} catch (\Exception $e) {
    // Falls back to template-based report
    return $this->generateTemplateBasedReport(...);
}
```
````
