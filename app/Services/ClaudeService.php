<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz;
use App\Models\Question;

class ClaudeService
{
    private string $apiKey;
    private string $baseUrl;
    private string $model;
    private int $maxTokens;
    private float $temperature;
    private bool $cacheEnabled;
    private int $timeout;

    public function __construct()
    {
        $this->apiKey = config('services.claude.key');
        $this->baseUrl = 'https://api.anthropic.com/v1';
        $this->model = config('services.claude.model', 'claude-3-5-sonnet-20241022');
        $this->maxTokens = (int) config('services.claude.max_tokens', 4000);
        $this->temperature = (float) config('services.claude.temperature', 0.7);
        $this->cacheEnabled = (bool) config('services.claude.cache_enabled', true);
        $this->timeout = (int) config('services.claude.timeout', 120);

        if (empty($this->apiKey)) {
            throw new \Exception('Claude API key is not configured. Please set CLAUDE_API_KEY in your .env file.');
        }
    }

    public function generateEducationalText(
        string $subject,
        int $gradeLevel,
        string $topic,
        string $textType = 'article',
        string $length = 'medium'
    ): string {
        $cacheKey = "educational_text_{$subject}_{$gradeLevel}_" . md5($topic . $textType . $length);

        if ($this->cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $prompt = $this->buildTextGenerationPrompt($subject, $gradeLevel, $topic, $textType, $length);

        try {
            $response = $this->sendRequest($prompt, [
                'max_tokens' => $this->getLengthTokens($length),
                'temperature' => 0.7
            ]);

            $text = $this->extractContent($response);
            $this->trackUsage('text_generation', 1);

            if ($this->cacheEnabled) {
                Cache::put($cacheKey, $text, now()->addHours(24));
            }

            Log::info('Educational text generated successfully', [
                'subject' => $subject,
                'grade_level' => $gradeLevel,
                'topic' => $topic,
                'text_length' => strlen($text)
            ]);

            return $text;
        } catch (\Exception $e) {
            Log::error('Educational text generation failed', [
                'error' => $e->getMessage(),
                'subject' => $subject,
                'topic' => $topic
            ]);
            throw new \Exception('فشل توليد النص التعليمي: ' . $e->getMessage());
        }
    }

    public function generateQuestionsFromText(
        string $text,
        string $subject,
        int $gradeLevel,
        array $roots
    ): array {
        Log::info('Generating questions from text', [
            'text_length' => strlen($text),
            'subject' => $subject,
            'roots' => $roots
        ]);

        $prompt = $this->buildQuestionsFromTextPrompt($text, $subject, $gradeLevel, $roots);

        try {
            $response = $this->sendRequest($prompt, [
                'temperature' => 0.3,
                'max_tokens' => 7000
            ]);

            $questions = $this->parseQuizResponse($response);
            $distributionValidated = $this->validateQuestionDistribution($questions, $roots);
            $qualityValidated = $distributionValidated; // Temporarily disable quality validation
            $this->trackUsage('questions_from_text', count($qualityValidated));
            Log::info('Questions generated successfully', [
                'total_generated' => count($questions),
                'validated_count' => count($qualityValidated)
            ]);

            return $qualityValidated;
        } catch (\Exception $e) {
            Log::error('Question generation from text failed', [
                'error' => $e->getMessage(),
                'text_length' => strlen($text)
            ]);
            throw new \Exception('فشل توليد الأسئلة من النص: ' . $e->getMessage());
        }
    }

    public function generateJuzoorQuiz(
        string $subject,
        int $gradeLevel,
        string $topic,
        array $roots,
        bool $includePassage = false,
        ?string $passageTopic = null,
        ?int $totalQuestions = null
    ): array {
        // Calculate total questions from roots if not provided
        if ($totalQuestions === null) {
            $totalQuestions = array_sum(array_map(function ($root) {
                return array_sum($root);
            }, $roots));
        }

        Log::info('Generating complete Juzoor quiz', [
            'subject' => $subject,
            'grade' => $gradeLevel,
            'topic' => $topic,
            'roots' => $roots,
            'include_passage' => $includePassage,
            'total_questions' => $totalQuestions
        ]);

        $prompt = $this->buildJuzoorQuizPrompt(
            $subject,
            $gradeLevel,
            $topic,
            $roots,
            $includePassage,
            $passageTopic,
            $totalQuestions
        );

        try {
            $response = $this->sendRequest($prompt, [
                'temperature' => 0.5,
                'max_tokens' => 4000
            ]);

            $result = $this->parseCompleteQuizResponse($response);
            $this->trackUsage('complete_quiz_generation', count($result['questions']));

            Log::info('Complete Juzoor quiz generated successfully', [
                'questions_count' => count($result['questions']),
                'has_passage' => !empty($result['passage'])
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Complete quiz generation failed', [
                'error' => $e->getMessage(),
                'subject' => $subject,
                'topic' => $topic
            ]);
            throw new \Exception('فشل توليد الاختبار: ' . $e->getMessage());
        }
    }

    public function generateQuestionSuggestions(
        string $question,
        array $options,
        string $correctAnswer,
        string $rootType,
        int $depthLevel,
        string $passage = ''
    ): array {
        $rootName = $this->getRootName($rootType);
        $optionsText = implode("\n", array_map(fn($opt, $idx) => chr(65 + $idx) . ") " . $opt, $options, array_keys($options)));
        $passageContext = !empty($passage) ? "السياق/النص المرجعي:\n{$passage}\n\n" : "";

        $prompt = "{$passageContext}السؤال الحالي:
{$question}

الخيارات:
{$optionsText}

الإجابة الصحيحة: {$correctAnswer}
نوع الجذر: {$rootName}
مستوى العمق: {$depthLevel}

المطلوب: تقديم اقتراحات لتحسين هذا السؤال.

يجب أن تكون الاستجابة بصيغة JSON صحيحة فقط، بدون أي نص إضافي:

{
  \"improved_question\": \"صياغة محسنة للسؤال\",
  \"improved_options\": [\"خيار محسن 1\", \"خيار محسن 2\", \"خيار محسن 3\", \"خيار محسن 4\"],
  \"alternative_question\": \"سؤال بديل مماثل\",
  \"alternative_options\": [\"خيار بديل 1\", \"خيار بديل 2\", \"خيار بديل 3\", \"خيار بديل 4\"],
  \"explanation\": \"توضيح للتحسينات المقترحة\"
}

أرجع JSON صحيح فقط:";

        try {
            $response = $this->sendRequest($prompt, [
                'max_tokens' => 1000,
                'temperature' => 0.6
            ]);

            $suggestions = $this->parseJsonResponse($response);
            $this->trackUsage('question_suggestions', 1);

            return $suggestions;
        } catch (\Exception $e) {
            Log::error('Question suggestions generation failed', [
                'error' => $e->getMessage(),
                'question' => substr($question, 0, 50)
            ]);
            throw new \Exception('فشل توليد الاقتراحات: ' . $e->getMessage());
        }
    }

    private function buildTextGenerationPrompt(
        string $subject,
        int $gradeLevel,
        string $topic,
        string $textType,
        string $length
    ): string {
        $subjectName = $this->getSubjectName($subject);
        $textTypeName = $this->getTextTypeName($textType);
        $lengthDescription = $this->getLengthDescription($length);

        $gradeGuidance = $this->getGradeLevelGuidance($gradeLevel);

        return "You are an educational content writer. Write {$textTypeName} directly without introductions, titles, or explanations.

Content Requirements:
- Topic: {$topic}
- Subject: {$subjectName} 
- Grade: {$gradeLevel}
- Length: {$lengthDescription}
- {$gradeGuidance}

Cultural Context Guidelines:
- Use examples from Arab/Middle Eastern environment
- Avoid content contradicting Islamic values
- Include traditional references when appropriate
- Use culturally familiar names and places

Technical Requirements:
- Start text immediately without preambles
- No titles or headers
- Appropriate for grade {$gradeLevel}
- Contains assessable information
- Output in Arabic only

Write the text:";
    }

    private function buildQuestionsFromTextPrompt(
        string $text,
        string $subject,
        int $gradeLevel,
        array $roots
    ): string {
        $subjectName = $this->getSubjectName($subject);
        $subjectName = $this->getSubjectName($subject);
        $gradeGuidance = $this->getGradeLevelGuidance($gradeLevel);
        $prompt = "You are an educational assistant specialized in creating test questions using the Juzoor educational model based on given text.

Given Text:
{$text}

Basic Information:
- Subject: {$subjectName}
- Grade: {$gradeLevel}
- {$gradeGuidance}

Juzoor Roots Model - Strict Classification Rules:

1. **جَوهر (jawhar): Identification & Definition**
   - Must start with: ما هو/ما هي/اذكر/عرّف/حدد/سمّ
   - Example: ما هو تعريف وسائل التواصل الاجتماعي؟
   - Tests ONLY: terminology, definitions, facts, names, classifications
   - NEVER includes: analysis, connections, applications, reasoning

2. **ذِهن (zihn): Analysis & Processing**  
   - Must start with: كيف/لماذا/حلل/استنتج/قارن/فسر
   - Example: لماذا تؤثر وسائل التواصل على الصحة النفسية؟
   - Tests ONLY: processes, causes, analysis, mechanisms, conclusions
   - NEVER includes: simple definitions, connections, future applications

3. **وَصلات (waslat): Relationships & Connections**
   - Must start with: ما العلاقة/اربط/قارن بين/كيف يؤثر على
   - Example: ما العلاقة بين إدمان وسائل التواصل وانخفاض الإنتاجية؟
   - Tests ONLY: links, comparisons, correlations, mutual effects
   - NEVER includes: definitions, isolated analysis, solutions

4. **رُؤية (roaya): Application & Innovation**
   - Must start with: كيف يمكن/اقترح/صمم/ابتكر/طبق
   - Example: كيف يمكن استخدام وسائل التواصل بشكل صحي؟
   - Tests ONLY: solutions, applications, strategies, innovations
   - NEVER includes: definitions, theoretical analysis, connections only

CRITICAL: All output must be in Arabic. Each root type requires completely different cognitive processes.

Depth Levels:
- Level 1: Direct information from text
- Level 2: Simple inferences
- Level 3: Deep analysis and complex conclusions

Each question must require completely different thinking patterns.

Create questions based on the given text with the following exact distribution:";

        $totalRequested = 0;
        foreach ($roots as $rootType => $levels) {
            $rootName = $this->getRootName($rootType);
            $prompt .= "\n\n{$rootName} ({$rootType}):";
            foreach ($levels as $level => $count) {
                if ($count > 0) {
                    $prompt .= "\n- المستوى {$level}: {$count} أسئلة بالضبط";
                    $totalRequested += $count;
                }
            }
        }

        $prompt .= "\n\nإجمالي الأسئلة المطلوبة: {$totalRequested} سؤال بالضبط

Mandatory Rules:
1. Produce exactly {$totalRequested} questions - no more, no less
2. Each question directly linked to given text
3. 4 separate options per question
4. Only one correct option
5. Use: \"jawhar\", \"zihn\", \"waslat\", \"roaya\"
6. Levels: 1, 2, or 3

Distractor Quality Rules:
- Wrong options must seem plausible at first glance
- Test common expected mistakes
- Gradually increase difficulty
- Avoid obvious hints to correct answer
- No grammatical inconsistencies
- Similar length and complexity to correct answer

ممنوعات قطعية:
❌ \"جميع ما سبق\"
❌ \"كل ما ذكر\" 
❌ \"لا شيء مما ذكر\"
❌ \"أ و ب\"
❌ \"ب و ج\"
❌ \"الأول والثاني\"
❌ أي خيار يجمع بين إجابتين

مثال صحيح:
خيارات: [\"الاثنين\", \"الثلاثاء\", \"الخميس\", \"الجمعة\"]

مثال خاطئ:
خيارات: [\"الاثنين\", \"الثلاثاء\", \"جميع ما سبق\", \"أ و ب\"]

REQUIRED JSON SCHEMA - Respond with valid JSON only:
{
   \"questions\": [
    {
      \"question\": \"نص السؤال\",
      \"options\": [\"خيار1\", \"خيار2\", \"خيار3\", \"خيار4\"],
      \"correct_answer\": \"الخيار الصحيح\",
      \"root_type\": \"jawhar|zihn|waslat|roaya\",
      \"depth_level\": 1|2|3,
      \"explanation\": \"توضيح مختصر للإجابة\"
    }
  ]
}

CRITICAL: 
- Return only valid JSON
- No additional text before or after JSON
- Exact 4 options per question
- correct_answer must match one option exactly

أنتج {$totalRequested} سؤال صالح بالضبط.";

        return $prompt;
    }

    private function buildJuzoorQuizPrompt(
        string $subject,
        int $gradeLevel,
        string $topic,
        array $roots,
        bool $includePassage,
        ?string $passageTopic,
        int $totalQuestions
    ): string {
        $subjectName = $this->getSubjectName($subject);
        $gradeGuidance = $this->getGradeLevelGuidance($gradeLevel);

        $prompt = "You are an educational expert specializing in the Juzoor (Roots) four-dimensional learning model.
    
    Task: Create a complete quiz following the Juzoor model.
    
    Quiz Details:
    - Subject: {$subjectName}
    - Grade: {$gradeLevel}
    - Topic: {$topic}
    - {$gradeGuidance}
    
    Juzoor Roots Model - Strict Classification Rules:
    
    1. **جَوهر (jawhar): Identification & Definition**
       - Must start with: ما هو/ما هي/اذكر/عرّف/حدد/سمّ
       - Tests ONLY: terminology, definitions, facts, names, classifications
       - NEVER includes: analysis, connections, applications
    
    2. **ذِهن (zihn): Analysis & Processing**
       - Must start with: كيف/لماذا/حلل/استنتج/قارن/فسر
       - Tests ONLY: processes, causes, analysis, mechanisms, conclusions
       - NEVER includes: simple definitions, connections, future applications
    
    3. **وَصلات (waslat): Relationships & Connections**
       - Must start with: ما العلاقة/اربط/قارن بين/كيف يؤثر على
       - Tests ONLY: links, comparisons, correlations, mutual effects
       - NEVER includes: definitions, isolated analysis, solutions
    
    4. **رُؤية (roaya): Application & Innovation**
       - Must start with: كيف يمكن/اقترح/صمم/ابتكر/طبق
       - Tests ONLY: solutions, applications, strategies, innovations
       - NEVER includes: definitions, theoretical analysis";

        if ($includePassage) {
            $passageTopicText = $passageTopic ?: $topic;
            $prompt .= "\n- يحتاج نص/قطعة؟ نعم، حول موضوع: {$passageTopicText}";
        }

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

        $prompt .= "\n\nنموذج الجُذور الأربعة:
1. جَوهر (الماهية) - ما هو؟ - التعريفات والفهم الأساسي
2. ذِهن (العقل) - كيف يعمل؟ - التحليل والتفكير النقدي
3. وَصلات (الروابط) - كيف يرتبط؟ - العلاقات والتكامل
4. رُؤية (البصيرة) - كيف نستخدمه؟ - التطبيق والابتكار

متطلبات الاختبار:
1. اتبع التوزيع المطلوب بدقة
2. كل سؤال له 4 خيارات (أ، ب، ج، د)
3. إجابة واحدة صحيحة فقط
4. تجنب خيارات مثل \"جميع ما سبق\" أو \"لا شيء مما ذكر\"
5. أسئلة متدرجة الصعوبة (المستوى 1 أسهل من 2، والمستوى 2 أسهل من 3)
6. مناسبة لمستوى الصف {$gradeLevel}

تنسيق الاستجابة (JSON):
```json
{";

        if ($includePassage) {
            $prompt .= '
  "passage": "النص/القطعة إذا كان مطلوباً، وإلا null",
  "passage_title": "عنوان النص إذا كان مطلوباً، وإلا null",';
        }

        $prompt .= '
  "questions": [
    {
      "question": "نص السؤال",
      "options": ["أ) الخيار الأول", "ب) الخيار الثاني", "ج) الخيار الثالث", "د) الخيار الرابع"],
      "correct_answer": "الإجابة الصحيحة كما هي في الخيارات",
      "root_type": "jawhar|zihn|waslat|roaya",
      "depth_level": 1
    }
  ]
}
```

يرجى إنشاء الاختبار كاملاً بالتنسيق المطلوب.';

        return $prompt;
    }

    private function sendRequest(string $prompt, array $overrides = []): array
    {
        $payload = [
            'model' => $overrides['model'] ?? $this->model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'max_tokens' => $overrides['max_tokens'] ?? $this->maxTokens,
            'temperature' => $overrides['temperature'] ?? $this->temperature
        ];

        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json'
        ])->timeout($this->timeout)->post($this->baseUrl . '/messages', $payload);

        if (!$response->successful()) {
            $errorMsg = $response->json('error.message') ?? 'Unknown API error';
            Log::error('Claude API request failed', [
                'status' => $response->status(),
                'error' => $errorMsg,
                'body' => $response->body()
            ]);
            throw new \Exception("Claude API error: {$errorMsg}");
        }

        return $response->json();
    }

    private function extractContent(array $response): string
    {
        if (!isset($response['content']) || !is_array($response['content'])) {
            Log::error('Invalid Claude response structure', ['response' => $response]);
            throw new \Exception('Invalid response structure');
        }

        $textContent = '';
        foreach ($response['content'] as $block) {
            if ($block['type'] === 'text') {
                $textContent .= $block['text'];
            }
        }

        return trim($textContent);
    }

    private function parseJsonResponse(array $response): array
    {
        $content = $this->extractContent($response);

        Log::info('Parsing JSON response', ['content_preview' => substr($content, 0, 200)]);

        $jsonData = null;

        if (preg_match('/```json\s*(.*?)\s*```/s', $content, $matches)) {
            $jsonString = trim($matches[1]);
            $jsonData = json_decode($jsonString, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $jsonData;
            }
        }

        if (preg_match('/\{[^{}]*(?:\{[^{}]*\}[^{}]*)*\}/s', $content, $matches)) {
            $jsonString = $matches[0];
            $jsonData = json_decode($jsonString, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $jsonData;
            }
        }

        $jsonStart = strpos($content, '{');
        if ($jsonStart !== false) {
            $potentialJson = substr($content, $jsonStart);
            $braceCount = 0;
            $jsonEnd = -1;

            for ($i = 0; $i < strlen($potentialJson); $i++) {
                if ($potentialJson[$i] === '{') {
                    $braceCount++;
                } elseif ($potentialJson[$i] === '}') {
                    $braceCount--;
                    if ($braceCount === 0) {
                        $jsonEnd = $i;
                        break;
                    }
                }
            }

            if ($jsonEnd !== -1) {
                $jsonString = substr($potentialJson, 0, $jsonEnd + 1);
                $jsonString = preg_replace('/,\s*}/', '}', $jsonString);
                $jsonString = preg_replace('/,\s*]/', ']', $jsonString);

                $jsonData = json_decode($jsonString, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $jsonData;
                }
            }
        }

        Log::error('Failed to parse JSON', [
            'content' => $content,
            'json_error' => json_last_error_msg()
        ]);

        throw new \Exception('No valid JSON found in AI response');
    }

    private function parseQuizResponse(array $response): array
    {
        $data = $this->parseJsonResponse($response);
        return $data['questions'] ?? [];
    }

    private function parseCompleteQuizResponse(array $response): array
    {
        $data = $this->parseJsonResponse($response);

        return [
            'passage' => $data['passage'] ?? null,
            'passage_title' => $data['passage_title'] ?? null,
            'questions' => $data['questions'] ?? []
        ];
    }

    private function validateQuestionDistribution(array $questions, array $requestedRoots): array
    {
        $validated = [];
        $distribution = [];

        $totalRequested = 0;
        foreach ($requestedRoots as $root => $levels) {
            foreach ($levels as $level => $count) {
                $key = "{$root}_{$level}";
                $distribution[$key] = [
                    'requested' => $count,
                    'added' => 0
                ];
                $totalRequested += $count;
            }
        }

        foreach ($questions as $question) {
            if (!$this->isQuestionValid($question)) {
                continue;
            }

            $rootType = strtolower(trim($question['root_type'] ?? ''));
            $depthLevel = (int) ($question['depth_level'] ?? 0);

            $rootTypeMap = [
                'jawhar' => 'jawhar',
                'جوهر' => 'jawhar',
                'جَوهر' => 'jawhar',
                'zihn' => 'zihn',
                'ذهن' => 'zihn',
                'ذِهن' => 'zihn',
                'waslat' => 'waslat',
                'وصلات' => 'waslat',
                'وَصلات' => 'waslat',
                'roaya' => 'roaya',
                'رؤية' => 'roaya',
                'رُؤية' => 'roaya',
            ];

            $normalizedRoot = $rootTypeMap[$rootType] ?? $rootType;
            $question['root_type'] = $normalizedRoot;
            $question['depth_level'] = $depthLevel;

            $key = "{$normalizedRoot}_{$depthLevel}";

            if (isset($distribution[$key]) && $distribution[$key]['added'] < $distribution[$key]['requested']) {
                $validated[] = $question;
                $distribution[$key]['added']++;
            } else {
                if (count($validated) < $totalRequested) {
                    $validated[] = $question;
                }
            }
        }

        $currentCount = count($validated);
        if ($currentCount < $totalRequested && count($questions) > $currentCount) {
            $remaining = $totalRequested - $currentCount;
            $unusedQuestions = array_slice($questions, $currentCount, $remaining);

            foreach ($unusedQuestions as $question) {
                if ($this->isQuestionValid($question)) {
                    $validated[] = $question;
                    if (count($validated) >= $totalRequested) {
                        break;
                    }
                }
            }
        }
        Log::info('DEBUG: Question counts at each step', [
            'ai_generated' => count($questions),
            'after_distribution_validation' => count($validated),
            'total_requested' => $totalRequested
        ]);
        Log::info('Question validation complete', [
            'total_requested' => $totalRequested,
            'total_generated' => count($questions),
            'validated_count' => count($validated),
            'distribution' => $distribution
        ]);

        return $validated;
    }
    private function validateQuestionsQuality(array $questions): array
    {
        $validationPrompt = "Review these questions for quality issues:
    
    " . json_encode($questions, JSON_UNESCAPED_UNICODE) . "
    
    Check for:
    1. Clear Arabic language formulation
    2. Accurate Juzoor root classification  
    3. Quality distractors (wrong options should be plausible)
    4. Appropriate difficulty level
    5. Diverse question patterns
    
    Return improved questions in same JSON format. Fix any issues found.
    
    Required JSON output:
    {
      \"questions\": [
    {
      \"question\": \"نص السؤال\",
      \"options\": [\"خيار1\", \"خيار2\", \"خيار3\", \"خيار4\"],
      \"correct_answer\": \"الخيار الصحيح\",
      \"root_type\": \"jawhar|zihn|waslat|roaya\",
      \"depth_level\": 1|2|3,
      \"explanation\": \"توضيح مختصر للإجابة\"
    }
      ]
    }
    CRITICAL: 
- Return only valid JSON
- No additional text before or after JSON
- Exact 4 options per question
- correct_answer must match one option exactly  
    ";

        try {
            $response = $this->sendRequest($validationPrompt, [
                'temperature' => 0.2,
                'max_tokens' => 3000
            ]);

            $validatedData = $this->parseJsonResponse($response);
            Log::info('Quality validation results', [
                'input_count' => count($questions),
                'output_count' => count($validatedData['questions'] ?? []),
                'validation_response_preview' => substr(json_encode($validatedData), 0, 200)
            ]);
            return $validatedData['questions'] ?? $questions;
        } catch (\Exception $e) {
            Log::warning('Question validation failed, using original questions', [
                'error' => $e->getMessage()
            ]);
            return $questions;
        }
    }
    private function isQuestionValid(array $question): bool
    {
        if (empty($question['question']) || empty($question['options']) || empty($question['correct_answer'])) {
            return false;
        }

        if (!is_array($question['options']) || count($question['options']) !== 4) {
            return false;
        }

        if (!in_array($question['correct_answer'], $question['options'])) {
            return false;
        }

        $forbiddenPatterns = [
            'جميع ما سبق',
            'كل ما ذكر',
            'لا شيء مما ذكر',
            'أ و ب',
            'ب و ج',
            'ج و د',
            'الأول والثاني',
            'الثاني والثالث',
            'النقطتان السابقتان',
            'الخيارات السابقة',
            'all of the above',
            'none of the above'
        ];

        foreach ($question['options'] as $option) {
            $optionLower = strtolower(trim($option));
            foreach ($forbiddenPatterns as $forbidden) {
                if (stripos($optionLower, strtolower($forbidden)) !== false) {
                    Log::warning('Question rejected for forbidden option', [
                        'option' => $option,
                        'pattern' => $forbidden
                    ]);
                    return false;
                }
            }
        }

        if (count($question['options']) !== count(array_unique($question['options']))) {
            return false;
        }

        return true;
    }

    private function trackUsage(string $operation, int $tokensUsed): void
    {
        try {
            Log::info('AI Usage', [
                'user_id' => Auth::check() ? Auth::id() : null,
                'operation' => $operation,
                'tokens_used' => $tokensUsed,
                'model' => $this->model
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to track AI usage', ['error' => $e->getMessage()]);
        }
    }

    public function testConnection(): array
    {
        try {
            $response = $this->sendRequest('مرحبا', [
                'max_tokens' => 50,
                'temperature' => 0
            ]);

            $result = [
                'connected' => true,
                'model' => $this->model,
                'response' => $this->extractContent($response)
            ];

            Log::info('Claude API connection test successful', $result);
            return $result;
        } catch (\Exception $e) {
            $result = [
                'connected' => false,
                'error' => $e->getMessage()
            ];

            Log::error('Claude API connection test failed', $result);
            return $result;
        }
    }

    public function generateCompletion(string $prompt, array $options = []): string
    {
        try {
            Log::info('Generating AI completion', ['prompt_length' => strlen($prompt)]);

            $response = $this->sendRequest($prompt, $options);
            $content = $this->extractContent($response);

            $this->trackUsage('general_completion', 1);

            return $content;
        } catch (\Exception $e) {
            Log::error('AI completion failed', ['error' => $e->getMessage()]);
            throw new \Exception('فشل توليد النص: ' . $e->getMessage());
        }
    }

    private function getSubjectName(string $subject): string
    {
        return match ($subject) {
            'arabic' => 'اللغة العربية',
            'english' => 'اللغة الإنجليزية',
            'hebrew' => 'اللغة العبرية',
            default => $subject
        };
    }

    private function getRootName(string $rootType): string
    {
        return match ($rootType) {
            'jawhar' => 'جَوهر (الماهية)',
            'zihn' => 'ذِهن (العقل)',
            'waslat' => 'وَصلات (الروابط)',
            'roaya' => 'رُؤية (البصيرة)',
            default => $rootType
        };
    }

    private function getTextTypeName(string $textType): string
    {
        return match ($textType) {
            'story' => 'قصة',
            'article' => 'مقال',
            'dialogue' => 'حوار',
            'description' => 'وصف',
            default => 'نص'
        };
    }

    private function getLengthDescription(string $length): string
    {
        return match ($length) {
            'short' => 'قصير (100-150 كلمة)',
            'medium' => 'متوسط (150-250 كلمة)',
            'long' => 'طويل (250-350 كلمة)',
            default => 'متوسط'
        };
    }

    private function getLengthTokens(string $length): int
    {
        return match ($length) {
            'short' => 500,
            'medium' => 800,
            'long' => 1200,
            default => 800
        };
    }
    private function getGradeLevelGuidance(int $gradeLevel): string
    {
        return match (true) {
            $gradeLevel <= 3 => "Vocabulary: Simple everyday words | Sentences: Short and direct | Concepts: Concrete, tangible examples",
            $gradeLevel <= 6 => "Vocabulary: Intermediate academic terms | Sentences: Compound structures | Concepts: Simple abstract ideas",
            $gradeLevel <= 9 => "Vocabulary: Advanced academic language | Sentences: Complex structures | Concepts: Abstract and theoretical"
        };
    }
    /**
     * Generate pedagogical AI report for quiz results
     */
    public function generatePedagogicalReport(
        $quiz,
        $results,
        array $rootsPerformance,
        array $questionStats
    ): array {
        try {
            $prompt = $this->buildPedagogicalReportPrompt($quiz, $results, $rootsPerformance, $questionStats);

            Log::info('Generating pedagogical report', [
                'quiz_id' => $quiz->id,
                'results_count' => $results->count(),
                'prompt_length' => strlen($prompt)
            ]);

            $response = $this->sendRequest($prompt, [
                'max_tokens' => 2000,
                'temperature' => 0.3
            ]);

            $content = $this->extractContent($response);
            $this->trackUsage('ai_report_generation', 1);

            return [
                'success' => true,
                'report_sections' => $this->parsePedagogicalReport($content),
                'raw_content' => $content
            ];

        } catch (\Exception $e) {
            Log::error('Pedagogical report generation failed', [
                'error' => $e->getMessage(),
                'quiz_id' => $quiz->id
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Build the enhanced prompt for pedagogical report generation
     */
    private function buildPedagogicalReportPrompt($quiz, $results, array $rootsPerformance, array $questionStats): string
    {
        $studentCount = $results->count();
        $subjectName = $quiz->subject->name ?? 'عام';
        $gradeLevel = $quiz->grade_level;

        // Get detailed answer analysis
        $answerAnalysis = $this->getDetailedAnswerAnalysis($quiz);
        $questionInsights = $this->getQuestionSpecificInsights($quiz);
        $learningPatterns = $this->detectLearningPatterns($quiz, $results);

        $prompt = "أنت خبير تربوي متخصص في تحليل أنماط التعلم وتشخيص الفجوات المفاهيمية. قم بتحليل البيانات التالية وتقديم رؤى عميقة وقابلة للتطبيق:\n\n";

        $prompt .= "معلومات الاختبار:\n";
        $prompt .= "- العنوان: {$quiz->title}\n";
        $prompt .= "- المادة: {$subjectName}\n";
        $prompt .= "- الصف: {$gradeLevel}\n";
        $prompt .= "- عدد الطلاب: {$studentCount}\n\n";

        $prompt .= "تحليل الأخطاء الشائعة:\n";
        foreach ($questionInsights as $insight) {
            $prompt .= "- السؤال {$insight['question_number']}: {$insight['common_mistakes']}\n";
        }

        $prompt .= "\nأنماط التعلم المكتشفة:\n";
        foreach ($learningPatterns as $pattern) {
            $prompt .= "- {$pattern}\n";
        }

        $prompt .= "\nتحليل تفصيلي للإجابات الخاطئة:\n";
        foreach ($answerAnalysis as $analysis) {
            $prompt .= $analysis . "\n";
        }

        $prompt .= "\nبناءً على هذا التحليل العميق، قم بإنشاء تقرير يركز على:\n";
        $prompt .= "1. تشخيص المفاهيم الخاطئة المحددة\n";
        $prompt .= "2. أنماط الأخطاء التي تكشف عن فجوات في التعلم\n";
        $prompt .= "3. توصيات محددة لكل مجموعة من الطلاب\n";
        $prompt .= "4. استراتيجيات تدريسية مخصصة لهذا الصف تحديداً\n\n";

        $prompt .= "استخدم التنسيق التالي مع التركيز على الرؤى العملية:\n\n";
        $prompt .= "[OVERVIEW]\n[تلخيص الاكتشافات الرئيسية والمفاهيم الخاطئة الأكثر شيوعاً]\n[/OVERVIEW]\n\n";

        $prompt .= "[JAWHAR_ANALYSIS]\n[تحليل الأخطاء في المعرفة الأساسية مع أمثلة محددة من الإجابات]\n[/JAWHAR_ANALYSIS]\n\n";
        $prompt .= "[ZIHN_ANALYSIS]\n[تحليل أخطاء التفكير النقدي مع أنماط الاستدلال الخاطئ]\n[/ZIHN_ANALYSIS]\n\n";
        $prompt .= "[WASLAT_ANALYSIS]\n[تحليل فشل الربط بين المفاهيم مع أمثلة محددة]\n[/WASLAT_ANALYSIS]\n\n";
        $prompt .= "[ROAYA_ANALYSIS]\n[تحليل ضعف التطبيق العملي مع اقتراحات لتحسين مهارات حل المشكلات]\n[/ROAYA_ANALYSIS]\n\n";

        $prompt .= "[GROUP_TIPS]\n[تقسيم الطلاب لمجموعات حسب أنماط أخطائهم مع استراتيجيات لكل مجموعة]\n[/GROUP_TIPS]\n\n";
        $prompt .= "[IMMEDIATE_ACTIONS]\n[3 إجراءات محددة للدرس القادم بناءً على الأخطاء المكتشفة]\n[/IMMEDIATE_ACTIONS]\n\n";
        $prompt .= "[LONGTERM_STRATEGIES]\n[خطة تعديل المنهج بناءً على نقاط الضعف المحددة]\n[/LONGTERM_STRATEGIES]\n\n";
        $prompt .= "[EDUCATIONAL_ALERTS]\n[تحذيرات من مفاهيم خاطئة قد تؤثر على التعلم المستقبلي]\n[/EDUCATIONAL_ALERTS]\n\n";
        $prompt .= "[BRIGHT_SPOTS]\n[نقاط القوة غير المتوقعة وكيفية البناء عليها]\n[/BRIGHT_SPOTS]\n\n";

        return $prompt;
    }

    /**
     * Get detailed analysis of wrong answers
     */
    private function getDetailedAnswerAnalysis($quiz): array
    {
        $analysis = [];

        foreach ($quiz->questions as $question) {
            $wrongAnswers = \App\Models\Answer::where('question_id', $question->id)
                ->where('is_correct', false)
                ->get();

            if ($wrongAnswers->count() > 0) {
                $wrongChoices = $wrongAnswers->groupBy('selected_answer');
                $mostCommonWrong = $wrongChoices->sortByDesc(function ($group) {
                    return $group->count();
                })->first();

                if ($mostCommonWrong && $mostCommonWrong->count() > 1) {
                    $analysis[] = "السؤال رقم {$question->id}: {$mostCommonWrong->count()} طلاب اختاروا '{$mostCommonWrong->first()->selected_answer}' بدلاً من الإجابة الصحيحة '{$question->correct_answer}'";
                }
            }
        }

        return $analysis;
    }

    /**
     * Get question-specific insights
     */
    private function getQuestionSpecificInsights($quiz): array
    {
        $insights = [];
        $questionNumber = 1;

        foreach ($quiz->questions as $question) {
            $totalAnswers = \App\Models\Answer::where('question_id', $question->id)->count();
            $correctAnswers = \App\Models\Answer::where('question_id', $question->id)
                ->where('is_correct', true)->count();

            if ($totalAnswers > 0) {
                $correctRate = ($correctAnswers / $totalAnswers) * 100;

                if ($correctRate < 50) {
                    $insights[] = [
                        'question_number' => $questionNumber,
                        'correct_rate' => round($correctRate),
                        'common_mistakes' => "نسبة إجابة منخفضة ({$correctRate}%) تشير لصعوبة في فهم المفهوم"
                    ];
                }
            }
            $questionNumber++;
        }

        return $insights;
    }

    /**
     * Detect learning patterns across questions
     */
    private function detectLearningPatterns($quiz, $results): array
    {
        $patterns = [];

        // Analyze root performance patterns
        foreach (['jawhar', 'zihn', 'waslat', 'roaya'] as $root) {
            $rootQuestions = $quiz->questions->where('root_type', $root);
            if ($rootQuestions->count() > 0) {
                $rootCorrect = 0;
                $rootTotal = 0;

                foreach ($rootQuestions as $question) {
                    $correctCount = \App\Models\Answer::where('question_id', $question->id)
                        ->where('is_correct', true)->count();
                    $totalCount = \App\Models\Answer::where('question_id', $question->id)->count();

                    $rootCorrect += $correctCount;
                    $rootTotal += $totalCount;
                }

                if ($rootTotal > 0) {
                    $rootPercentage = ($rootCorrect / $rootTotal) * 100;

                    if ($rootPercentage < 60) {
                        $rootName = $this->getRootName($root);
                        $patterns[] = "ضعف واضح في {$rootName} ({$rootPercentage}%) يتطلب تدخل تعليمي مركز";
                    } elseif ($rootPercentage > 85) {
                        $rootName = $this->getRootName($root);
                        $patterns[] = "تميز في {$rootName} ({$rootPercentage}%) يمكن البناء عليه لتطوير جوانب أخرى";
                    }
                }
            }
        }

        return $patterns;
    }

    /**
     * Parse the AI response into structured report sections
     */
    private function parsePedagogicalReport(string $content): array
    {
        $sections = [];

        $patterns = [
            'overview' => '/\[OVERVIEW\](.*?)\[\/OVERVIEW\]/s',
            'jawhar_analysis' => '/\[JAWHAR_ANALYSIS\](.*?)\[\/JAWHAR_ANALYSIS\]/s',
            'zihn_analysis' => '/\[ZIHN_ANALYSIS\](.*?)\[\/ZIHN_ANALYSIS\]/s',
            'waslat_analysis' => '/\[WASLAT_ANALYSIS\](.*?)\[\/WASLAT_ANALYSIS\]/s',
            'roaya_analysis' => '/\[ROAYA_ANALYSIS\](.*?)\[\/ROAYA_ANALYSIS\]/s',
            'group_tips' => '/\[GROUP_TIPS\](.*?)\[\/GROUP_TIPS\]/s',
            'immediate_actions' => '/\[IMMEDIATE_ACTIONS\](.*?)\[\/IMMEDIATE_ACTIONS\]/s',
            'longterm_strategies' => '/\[LONGTERM_STRATEGIES\](.*?)\[\/LONGTERM_STRATEGIES\]/s',
            'educational_alerts' => '/\[EDUCATIONAL_ALERTS\](.*?)\[\/EDUCATIONAL_ALERTS\]/s',
            'bright_spots' => '/\[BRIGHT_SPOTS\](.*?)\[\/BRIGHT_SPOTS\]/s'
        ];

        foreach ($patterns as $section => $pattern) {
            if (preg_match($pattern, $content, $matches)) {
                $sections[$section] = trim($matches[1]);
            } else {
                $sections[$section] = '';
            }
        }

        return $sections;
    }
}