<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use App\Models\Result;
use App\Models\Quiz;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ClaudeService
{
    private Client $client;
    private string $apiKey;
    private string $model;
    private int $maxTokens;
    private float $temperature;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 60, // Increased from 30
            'connect_timeout' => 15,
            'verify' => false, // For local development
        ]);

        $this->apiKey = config('services.claude.key');
        $this->model = config('services.claude.model', 'claude-3-sonnet-20240229');
        $this->maxTokens = config('services.claude.max_tokens', 4000);
        $this->temperature = config('services.claude.temperature', 0.7);

        if (empty($this->apiKey)) {
            throw new \Exception('Claude API key is not configured');
        }
    }

    /**
     * Generate a Juzoor educational quiz using Claude AI
     */
    public function generateJuzoorQuiz(
        string $subject,
        string $gradeLevel,
        string $topic,
        array $rootSettings,
        bool $includePassage = false,
        ?string $passageTopic = null
    ): array {
        $cacheKey = $this->generateCacheKey('quiz', compact('subject', 'gradeLevel', 'topic', 'rootSettings'));

        // Check cache first (optional)
        if (config('services.claude.cache_enabled', false)) {
            $cached = Cache::get($cacheKey);
            if ($cached) {
                Log::info('Using cached quiz generation');
                return $cached;
            }
        }

        try {
            $prompt = $this->buildJuzoorPrompt($subject, $gradeLevel, $topic, $rootSettings, $includePassage, $passageTopic);

            $response = $this->makeApiRequest($prompt);

            $parsedResponse = $this->parseQuizResponse($response);

            // Cache the response (optional)
            if (config('services.claude.cache_enabled', false)) {
                Cache::put($cacheKey, $parsedResponse, now()->addHours(24));
            }

            return $parsedResponse;

        } catch (GuzzleException $e) {
            Log::error('Claude API request failed', [
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
            throw new \Exception('فشل الاتصال بخدمة الذكاء الاصطناعي. الرجاء المحاولة مرة أخرى.');
        } catch (\Exception $e) {
            Log::error('Quiz generation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Generate a detailed report for quiz results
     */
    public function generateResultReport(Result $result, Quiz $quiz): string
    {
        try {
            $prompt = $this->buildReportPrompt($result, $quiz);

            $response = $this->makeApiRequest($prompt, 2000);

            return $this->cleanReportResponse($response);

        } catch (\Exception $e) {
            Log::error('Report generation failed', [
                'error' => $e->getMessage(),
                'result_id' => $result->id,
                'quiz_id' => $quiz->id
            ]);
            throw new \Exception('فشل توليد التقرير. الرجاء المحاولة مرة أخرى.');
        }
    }

    /**
     * Make API request to Claude
     */
    private function makeApiRequest(string $prompt, ?int $maxTokens = null): string
    {
        try {
            $response = $this->client->post('https://api.anthropic.com/v1/messages', [
                'headers' => [
                    'x-api-key' => $this->apiKey,
                    'anthropic-version' => '2023-06-01',
                    'content-type' => 'application/json',
                ],
                'json' => [
                    'model' => $this->model,
                    'max_tokens' => $maxTokens ?? $this->maxTokens,
                    'temperature' => $this->temperature,
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ]
                ],
                'timeout' => config('services.claude.timeout', 60),
                'connect_timeout' => 15,
                'verify' => false // Disable SSL verification for local development
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            Log::info('Claude API Response', [
                'status' => $statusCode,
                'body_length' => strlen($body)
            ]);

            $data = json_decode($body, true);

            if (!isset($data['content'][0]['text'])) {
                Log::error('Invalid Claude API response structure', ['response' => $data]);
                throw new \Exception('Invalid response structure from AI service');
            }

            return $data['content'][0]['text'];

        } catch (GuzzleException $e) {
            Log::error('Claude API connection error', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'api_key_exists' => !empty($this->apiKey),
                'api_key_length' => strlen($this->apiKey)
            ]);

            if ($e->getCode() === 401) {
                throw new \Exception('مفتاح API غير صحيح. تحقق من إعدادات Claude API.');
            } elseif ($e->getCode() === 0) {
                throw new \Exception('لا يمكن الاتصال بخادم Claude. تحقق من اتصال الإنترنت.');
            }

            throw new \Exception('فشل الاتصال بخدمة الذكاء الاصطناعي: ' . $e->getMessage());
        }
    }

    /**
     * Build the prompt for quiz generation
     */
    private function buildJuzoorPrompt(
        string $subject,
        string $gradeLevel,
        string $topic,
        array $rootSettings,
        bool $includePassage,
        ?string $passageTopic
    ): string {
        $subjectNames = [
            'arabic' => 'اللغة العربية',
            'english' => 'اللغة الإنجليزية',
            'hebrew' => 'اللغة العبرية'
        ];

        $subjectName = $subjectNames[$subject] ?? $subject;

        $prompt = <<<PROMPT
أنت خبير تعليمي متخصص في نموذج جُذور التعليمي. قم بإنشاء اختبار تعليمي عالي الجودة وفق المواصفات التالية:

معلومات الاختبار:
- المادة: $subjectName
- الصف: $gradeLevel
- الموضوع: $topic

نموذج جُذور التعليمي:
1. جَوهر (Jawhar): "ما هو؟" - التعريفات والمفاهيم الأساسية
2. ذِهن (Zihn): "كيف يعمل؟" - الآليات والعمليات والتحليل
3. وَصلات (Waslat): "كيف يرتبط؟" - العلاقات والروابط بين المفاهيم
4. رُؤية (Roaya): "كيف نستخدمه؟" - التطبيق العملي والإبداع

مستويات العمق:
- المستوى 1: أسئلة بسيطة ومباشرة (تذكر وفهم)
- المستوى 2: أسئلة تحليلية (تطبيق وتحليل)
- المستوى 3: أسئلة عميقة (تقييم وإبداع)

الأسئلة المطلوبة:
PROMPT;

        $totalQuestions = 0;
        foreach ($rootSettings as $rootKey => $levels) {
            if (!is_array($levels))
                continue;

            foreach ($levels as $level) {
                if (is_array($level) && isset($level['count']) && $level['count'] > 0) {
                    $rootName = $this->getRootName($rootKey);
                    $depth = $level['depth'] ?? 1;
                    $count = $level['count'];
                    $totalQuestions += $count;
                    $prompt .= "\n- $rootName (مستوى $depth): $count أسئلة";
                }
            }
        }

        if ($includePassage) {
            $passageAbout = $passageTopic ?: $topic;
            $prompt .= "\n\nنص القراءة:";
            $prompt .= "\n- أنشئ نص قراءة تعليمي حول: $passageAbout";
            $prompt .= "\n- الطول: 150-250 كلمة";
            $prompt .= "\n- مناسب للصف $gradeLevel";
            $prompt .= "\n- يحتوي على معلومات غنية لبناء الأسئلة عليها";
        }

        $prompt .= <<<PROMPT

التعليمات الهامة:
1. كل سؤال من نوع اختيار من متعدد (4 خيارات)
2. الأسئلة باللغة العربية الفصحى
3. مناسبة لمستوى الصف المحدد
4. كل سؤال يتبع فلسفة الجذر المخصص له بدقة
5. الخيارات متقاربة في الطول ومنطقية
6. خيار واحد فقط صحيح تماماً
7. تجنب العبارات النافية في الأسئلة
8. نوّع في صياغة الأسئلة

الصيغة المطلوبة (JSON):
{
PROMPT;

        if ($includePassage) {
            $prompt .= <<<PROMPT

  "passage_title": "عنوان النص المناسب",
  "passage": "نص القراءة الكامل هنا...",
PROMPT;
        }

        $prompt .= <<<PROMPT

  "questions": [
    {
      "question": "نص السؤال الواضح والمحدد",
      "root_type": "jawhar|zihn|waslat|roaya",
      "depth_level": 1|2|3,
      "options": ["الخيار الأول", "الخيار الثاني", "الخيار الثالث", "الخيار الرابع"],
      "correct_answer": "النص الكامل للإجابة الصحيحة من الخيارات"
    }
  ]
}

تذكر: أنشئ بالضبط $totalQuestions سؤال حسب التوزيع المطلوب.
PROMPT;

        return $prompt;
    }

    /**
     * Build the prompt for report generation
     */
    private function buildReportPrompt(Result $result, Quiz $quiz): string
    {
        $scores = $result->scores;
        $userName = $result->user->name ?? 'الطالب';
        $correctAnswers = $result->answers->where('is_correct', true)->count();
        $totalAnswers = $result->answers->count();

        $prompt = <<<PROMPT
قم بإنشاء تقرير تعليمي تفصيلي ومُحفز لنتائج اختبار نموذج جُذور.

بيانات الاختبار:
- العنوان: {$quiz->title}
- المادة: {$quiz->subject}
- الصف: {$quiz->grade_level}
- الطالب: $userName
- الإجابات الصحيحة: $correctAnswers من $totalAnswers
- النتيجة الإجمالية: {$result->total_score}%

أداء الجذور:
- جَوهر (الأساس والتعريفات): {$scores['jawhar']}%
- ذِهن (التحليل والفهم): {$scores['zihn']}%
- وَصلات (الربط والعلاقات): {$scores['waslat']}%
- رُؤية (التطبيق والإبداع): {$scores['roaya']}%

تفاصيل الأداء:
PROMPT;

        $rootPerformance = [
            'jawhar' => ['correct' => 0, 'total' => 0],
            'zihn' => ['correct' => 0, 'total' => 0],
            'waslat' => ['correct' => 0, 'total' => 0],
            'roaya' => ['correct' => 0, 'total' => 0]
        ];

        foreach ($result->answers as $answer) {
            $root = $answer->question->root_type;
            $rootPerformance[$root]['total']++;
            if ($answer->is_correct) {
                $rootPerformance[$root]['correct']++;
            }
        }

        foreach ($rootPerformance as $root => $performance) {
            if ($performance['total'] > 0) {
                $rootName = $this->getRootName($root);
                $prompt .= "\n- $rootName: {$performance['correct']}/{$performance['total']} إجابات صحيحة";
            }
        }

        $prompt .= <<<PROMPT

المطلوب في التقرير:
1. مقدمة تحفيزية قصيرة
2. تحليل الأداء في كل جذر:
   - نقاط القوة (ما أتقنه الطالب)
   - فرص التحسين (بصياغة إيجابية)
   - نصائح عملية محددة
3. خطة تطوير مقترحة (3-5 خطوات واضحة)
4. رسالة تشجيعية ختامية

ملاحظات هامة:
- استخدم لغة إيجابية ومحفزة
- ركز على النمو والتطور
- تجنب كلمات الفشل أو الضعف
- قدم نصائح عملية قابلة للتطبيق
- اجعل التقرير شخصياً وموجهاً للطالب
PROMPT;

        return $prompt;
    }

    /**
     * Parse the quiz response from Claude
     */
    private function parseQuizResponse(string $response): array
    {
        // Try to extract JSON from the response
        $jsonMatch = [];
        preg_match('/\{.*\}/s', $response, $jsonMatch);

        if (empty($jsonMatch)) {
            Log::error('No JSON found in response', ['response' => substr($response, 0, 500)]);
            throw new \Exception('لم يتم توليد الأسئلة بالصيغة الصحيحة');
        }

        $jsonData = json_decode($jsonMatch[0], true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON decode error', [
                'error' => json_last_error_msg(),
                'json' => substr($jsonMatch[0], 0, 500)
            ]);
            throw new \Exception('خطأ في معالجة البيانات المولدة');
        }

        if (!isset($jsonData['questions']) || !is_array($jsonData['questions'])) {
            throw new \Exception('لم يتم العثور على الأسئلة في الاستجابة');
        }

        // Validate questions
        $validatedQuestions = [];
        foreach ($jsonData['questions'] as $index => $question) {
            if ($this->validateQuestion($question)) {
                $validatedQuestions[] = $question;
            } else {
                Log::warning('Invalid question skipped', ['index' => $index, 'question' => $question]);
            }
        }

        if (empty($validatedQuestions)) {
            throw new \Exception('لم يتم توليد أي أسئلة صالحة');
        }

        $jsonData['questions'] = $validatedQuestions;

        return $jsonData;
    }

    /**
     * Validate a single question
     */
    private function validateQuestion(array $question): bool
    {
        return isset($question['question']) &&
            isset($question['root_type']) &&
            isset($question['depth_level']) &&
            isset($question['options']) &&
            isset($question['correct_answer']) &&
            is_array($question['options']) &&
            count($question['options']) >= 2 &&
            in_array($question['root_type'], ['jawhar', 'zihn', 'waslat', 'roaya']) &&
            in_array($question['depth_level'], [1, 2, 3]);
    }

    /**
     * Clean report response
     */
    private function cleanReportResponse(string $response): string
    {
        // Remove any markdown code blocks
        $response = preg_replace('/```[\s\S]*?```/', '', $response);

        // Convert markdown headers to HTML
        $response = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $response);
        $response = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $response);
        $response = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $response);

        // Convert markdown bold to HTML
        $response = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $response);

        // Convert line breaks
        $response = nl2br(trim($response));

        return $response;
    }

    /**
     * Get root name in Arabic
     */
    private function getRootName(string $rootKey): string
    {
        $names = [
            'jawhar' => 'جَوهر',
            'zihn' => 'ذِهن',
            'waslat' => 'وَصلات',
            'roaya' => 'رُؤية'
        ];

        return $names[$rootKey] ?? $rootKey;
    }

    /**
     * Generate cache key for responses
     */
    private function generateCacheKey(string $type, array $params): string
    {
        return 'claude_' . $type . '_' . md5(json_encode($params));
    }
}