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
                'max_tokens' => 4000
            ]);

            $questions = $this->parseQuizResponse($response);
            $validatedQuestions = $this->validateQuestionDistribution($questions, $roots);

            $this->trackUsage('questions_from_text', count($validatedQuestions));

            Log::info('Questions generated successfully', [
                'total_generated' => count($questions),
                'validated_count' => count($validatedQuestions)
            ]);

            return $validatedQuestions;
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
        ?string $passageTopic = null
    ): array {
        Log::info('Generating complete Juzoor quiz', [
            'subject' => $subject,
            'grade' => $gradeLevel,
            'topic' => $topic,
            'roots' => $roots,
            'include_passage' => $includePassage
        ]);

        $prompt = $this->buildJuzoorQuizPrompt(
            $subject,
            $gradeLevel,
            $topic,
            $roots,
            $includePassage,
            $passageTopic
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

        return "أنت كاتب محتوى تعليمي. اكتب {$textTypeName} مباشرة بدون مقدمات أو عناوين أو شروحات.

الموضوع: {$topic}
المادة: {$subjectName}
الصف: {$gradeLevel}
الطول: {$lengthDescription}

متطلبات:
- ابدأ النص مباشرة
- لا تكتب \"هنا قصة\" أو \"إليكم نص\" أو أي مقدمة
- لا تضع عنواناً
- مناسب للصف {$gradeLevel}
- يحتوي معلومات قابلة للتقييم

اكتب النص فقط:";
    }

    private function buildQuestionsFromTextPrompt(
        string $text,
        string $subject,
        int $gradeLevel,
        array $roots
    ): string {
        $subjectName = $this->getSubjectName($subject);

        $prompt = "أنت مساعد تعليمي متخصص في إنشاء أسئلة اختبار باستخدام نموذج جُذور التعليمي بناءً على نص معطى.

النص المعطى:
{$text}

المعلومات الأساسية:
- المادة: {$subjectName}
- الصف: {$gradeLevel}

نموذج جُذور - قواعد صارمة لكل نوع:

1. جَوهر (jawhar): ما هو؟ - أسئلة تعريفية محضة
   - مثال: ما هو تعريف وسائل التواصل الاجتماعي؟
   - تركز على: التعريفات، الحقائق المباشرة، الأسماء، الأرقام

2. ذِهن (zihn): كيف يعمل؟ - أسئلة تحليلية فقط
   - مثال: كيف تؤثر وسائل التواصل على الصحة النفسية؟
   - تركز على: العمليات، الأسباب، التحليل، الآليات

3. وَصلات (waslat): كيف يرتبط؟ - أسئلة علاقات فقط
   - مثال: ما العلاقة بين إدمان وسائل التواصل وانخفاض الإنتاجية؟
   - تركز على: الروابط، المقارنات، التأثيرات المتبادلة

4. رُؤية (roaya): كيف نستخدمه؟ - أسئلة تطبيقية فقط
   - مثال: كيف يمكن استخدام وسائل التواصل بشكل صحي؟
   - تركز على: الحلول، التطبيقات، الاستراتيجيات

مستويات العمق:
- المستوى 1: معلومات مباشرة من النص
- المستوى 2: استنتاجات بسيطة 
- المستوى 3: تحليل عميق واستنتاجات معقدة

كل سؤال يجب أن يكون مختلفاً تماماً في نوع التفكير المطلوب.

المطلوب إنشاء أسئلة بناءً على النص المعطى بالتوزيع التالي بدقة تامة:";

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

قواعد إلزامية:
1. أنتج بالضبط {$totalRequested} سؤال - لا أقل ولا أكثر
2. كل سؤال مرتبط مباشرة بالنص المعطى
3. 4 خيارات منفصلة لكل سؤال
4. خيار واحد صحيح فقط
5. استخدم: \"jawhar\", \"zihn\", \"waslat\", \"roaya\"
6. مستويات: 1, 2, أو 3

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

JSON مطلوب:
{
    \"questions\": [
        {
            \"question\": \"نص السؤال\",
            \"options\": [\"خيار1\", \"خيار2\", \"خيار3\", \"خيار4\"],
            \"correct_answer\": \"الخيار الصحيح\",
            \"root_type\": \"jawhar\",
            \"depth_level\": 1
        }
    ]
}

أنتج {$totalRequested} سؤال صالح بالضبط.";

        return $prompt;
    }

    private function buildJuzoorQuizPrompt(
        string $subject,
        int $gradeLevel,
        string $topic,
        array $roots,
        bool $includePassage,
        ?string $passageTopic
    ): string {
        $subjectName = $this->getSubjectName($subject);

        $prompt = "أنت خبير تعليمي متخصص في نموذج الجُذور الأربعة التعليمي.

المطلوب: إنشاء اختبار كامل وفق نموذج الجُذور الأربعة

تفاصيل الاختبار:
- المادة: {$subjectName}
- الصف: {$gradeLevel}
- الموضوع: {$topic}";

        if ($includePassage) {
            $passageTopicText = $passageTopic ?: $topic;
            $prompt .= "\n- يحتاج نص/قطعة؟ نعم، حول موضوع: {$passageTopicText}";
        }

        $prompt .= "\n\nتوزيع الأسئلة المطلوب:";
        $totalQuestions = 0;

        foreach ($roots as $rootType => $levels) {
            $rootName = $this->getRootName($rootType);
            $prompt .= "\n\n{$rootName}:";
            foreach ($levels as $level => $count) {
                if ($count > 0) {
                    $prompt .= "\n- المستوى {$level}: {$count} أسئلة";
                    $totalQuestions += $count;
                }
            }
        }

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

        Log::info('Question validation complete', [
            'total_requested' => $totalRequested,
            'total_generated' => count($questions),
            'validated_count' => count($validated),
            'distribution' => $distribution
        ]);

        return $validated;
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
}