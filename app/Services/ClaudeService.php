<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use App\Models\Result;
use App\Models\Quiz;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;

class ClaudeService
{
    private Client $client;
    private array $config;

    public function __construct()
    {
        // Initialize configuration with fallbacks
        $this->config = [
            'api_key' => env('CLAUDE_API_KEY', ''),
            'model' => env('CLAUDE_MODEL', 'claude-3-sonnet-20240229'),
            'max_tokens' => (int) env('CLAUDE_MAX_TOKENS', 4000),
            'temperature' => (float) env('CLAUDE_TEMPERATURE', 0.7),
            'cache_enabled' => (bool) env('CLAUDE_CACHE_ENABLED', false),
            'timeout' => (int) env('CLAUDE_TIMEOUT', 60),
        ];

        // Validate API key
        if (empty($this->config['api_key'])) {
            throw new Exception('Claude API key is not configured. Please set CLAUDE_API_KEY in your .env file.');
        }

        // Initialize HTTP client with proper SSL handling
        $clientConfig = [
            'timeout' => $this->config['timeout'],
            'connect_timeout' => 15,
            'http_errors' => false, // Handle errors manually
        ];

        // SSL Configuration for different environments
        if (app()->environment('local')) {
            // For local development on Windows with XAMPP
            // Option 1: Disable SSL verification (easiest for local dev)
            $clientConfig['verify'] = false;
            
            // Option 2: Use Windows certificate store (uncomment if Option 1 doesn't work)
            // $clientConfig['verify'] = true;
            // $clientConfig['curl'] = [
            //     CURLOPT_CAINFO => env('CURL_CA_BUNDLE', 'C:\xampp\apache\bin\curl-ca-bundle.crt'),
            // ];
            
            Log::info('ClaudeService: SSL verification disabled for local development');
        } else {
            // For production, always verify SSL
            $clientConfig['verify'] = true;
        }

        // Add proxy support if needed (for corporate networks)
        if (env('HTTP_PROXY')) {
            $clientConfig['proxy'] = [
                'http' => env('HTTP_PROXY'),
                'https' => env('HTTPS_PROXY', env('HTTP_PROXY')),
            ];
        }

        $this->client = new Client($clientConfig);
        
        Log::info('ClaudeService initialized', [
            'environment' => app()->environment(),
            'ssl_verify' => $clientConfig['verify'],
            'timeout' => $this->config['timeout'],
            'model' => $this->config['model']
        ]);
    }

    /**
     * Generate a Juzoor quiz with optional passage
     */
    public function generateJuzoorQuiz(
        string $subject,
        string $gradeLevel,
        string $topic,
        array $rootSettings,
        bool $includePassage = false,
        ?string $passageTopic = null,
        ?string $existingPassage = null
    ): array {
        try {
            // Build the appropriate prompt
            if ($existingPassage) {
                // Generate questions for existing passage
                $prompt = $this->buildPassageBasedPrompt(
                    $existingPassage,
                    $passageTopic ?? $topic,
                    $subject,
                    $gradeLevel,
                    $rootSettings
                );
            } else {
                // Generate new quiz (with optional passage generation)
                $prompt = $this->buildJuzoorPrompt(
                    $subject,
                    $gradeLevel,
                    $topic,
                    $rootSettings,
                    $includePassage,
                    $passageTopic
                );
            }

            // Make API request
            $response = $this->sendRequest($prompt);

            // Parse and validate response
            return $this->parseQuizResponse($response);

        } catch (Exception $e) {
            Log::error('Quiz generation failed', [
                'error' => $e->getMessage(),
                'subject' => $subject,
                'grade' => $gradeLevel,
                'topic' => $topic
            ]);
            throw $e;
        }
    }

    /**
     * Generate questions specifically for a given passage
     */
    public function generateQuestionsForPassage(
        string $passage,
        string $passageTitle,
        string $subject,
        string $gradeLevel,
        array $rootSettings
    ): array {
        return $this->generateJuzoorQuiz(
            $subject,
            $gradeLevel,
            $passageTitle,
            $rootSettings,
            false,
            null,
            $passage
        );
    }

    /**
     * Generate a detailed result report
     */
    public function generateResultReport(Result $result, Quiz $quiz): string
    {
        try {
            $prompt = $this->buildReportPrompt($result, $quiz);
            $response = $this->sendRequest($prompt, 2000);
            return $this->formatReportResponse($response);
        } catch (Exception $e) {
            Log::error('Report generation failed', [
                'error' => $e->getMessage(),
                'result_id' => $result->id,
                'quiz_id' => $quiz->id
            ]);
            throw new Exception('فشل توليد التقرير. الرجاء المحاولة مرة أخرى.');
        }
    }

    /**
     * Send request to Claude API
     */
    private function sendRequest(string $prompt, ?int $maxTokens = null): string
    {
        $maxTokens = $maxTokens ?? $this->config['max_tokens'];

        try {
            Log::info('Sending Claude API request', [
                'model' => $this->config['model'],
                'max_tokens' => $maxTokens,
                'prompt_length' => strlen($prompt)
            ]);

            $response = $this->client->post('https://api.anthropic.com/v1/messages', [
                'headers' => [
                    'x-api-key' => $this->config['api_key'],
                    'anthropic-version' => '2023-06-01',
                    'content-type' => 'application/json',
                ],
                'json' => [
                    'model' => $this->config['model'],
                    'max_tokens' => $maxTokens,
                    'temperature' => $this->config['temperature'],
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ]
                ]
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            if ($statusCode !== 200) {
                Log::error('Claude API error response', [
                    'status' => $statusCode,
                    'body' => $body
                ]);
                throw new Exception($this->getErrorMessage($statusCode, $body));
            }

            $data = json_decode($body, true);

            if (!isset($data['content'][0]['text'])) {
                throw new Exception('استجابة غير متوقعة من خدمة الذكاء الاصطناعي');
            }

            return $data['content'][0]['text'];

        } catch (GuzzleException $e) {
            Log::error('Claude API connection error', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'type' => get_class($e)
            ]);

            // Check for SSL-specific errors
            if (strpos($e->getMessage(), 'SSL') !== false || 
                strpos($e->getMessage(), 'certificate') !== false ||
                strpos($e->getMessage(), 'cURL error 60') !== false) {
                
                Log::error('SSL Certificate Error Detected', [
                    'environment' => app()->environment(),
                    'verify_setting' => $this->client->getConfig('verify')
                ]);
                
                throw new Exception('خطأ في شهادة الأمان (SSL). تأكد من إعدادات البيئة المحلية.');
            }

            throw new Exception($this->getConnectionErrorMessage($e));
        } catch (Exception $e) {
            Log::error('Unexpected error in Claude API request', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Build prompt for Juzoor quiz generation
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

        // Start building the prompt
        $prompt = <<<PROMPT
أنت خبير تعليمي متخصص في نموذج جُذور التعليمي. أنشئ اختبار تعليمي متميز وفق هذه المواصفات:

📚 معلومات الاختبار:
- المادة: $subjectName
- الصف: $gradeLevel
- الموضوع: $topic

🌱 نموذج جُذور التعليمي:
• جَوهر (Jawhar) 🎯: "ما هو؟" - التعريفات والمفاهيم الأساسية
• ذِهن (Zihn) 🧠: "كيف يعمل؟" - الآليات والعمليات والتحليل
• وَصلات (Waslat) 🔗: "كيف يرتبط؟" - العلاقات والروابط بين المفاهيم
• رُؤية (Roaya) 👁️: "كيف نستخدمه؟" - التطبيق العملي والإبداع

📊 مستويات العمق:
• المستوى 1: أسئلة أساسية (تذكر وفهم بسيط)
• المستوى 2: أسئلة تحليلية (تطبيق وتحليل)
• المستوى 3: أسئلة متقدمة (تقييم وإبداع)

PROMPT;

        // Add passage instructions if needed
        if ($includePassage) {
            $passageAbout = $passageTopic ?: $topic;
            $prompt .= <<<PROMPT

📖 نص القراءة المطلوب:
- أنشئ نص قراءة تعليمي ممتع حول: $passageAbout
- الطول: 150-250 كلمة
- مناسب تماماً للصف $gradeLevel
- يحتوي على معلومات غنية ومتنوعة
- مكتوب بأسلوب جذاب وواضح

⚠️ مهم جداً: جميع الأسئلة يجب أن تكون مبنية على النص المُنشأ:
- كل سؤال يختبر فهم جزء محدد من النص
- لا تسأل عن معلومات غير موجودة في النص
- نوّع الأسئلة لتغطي أجزاء مختلفة من النص
- اربط كل سؤال بالنص بشكل واضح

PROMPT;
        }

        // Add questions requirements
        $prompt .= "\n\n📝 الأسئلة المطلوبة:\n";
        $totalQuestions = 0;

        foreach ($rootSettings as $rootKey => $levels) {
            if (!is_array($levels)) continue;
            
            foreach ($levels as $level) {
                if (isset($level['count']) && $level['count'] > 0) {
                    $rootName = $this->getRootName($rootKey);
                    $depth = $level['depth'] ?? 1;
                    $count = $level['count'];
                    $totalQuestions += $count;
                    $prompt .= "• $rootName (مستوى $depth): $count أسئلة\n";
                }
            }
        }

        // Add format instructions
        $prompt .= <<<PROMPT

📋 تعليمات التنسيق:
1. كل سؤال من نوع اختيار من متعدد (4 خيارات بالضبط)
2. استخدم اللغة العربية الفصحى الواضحة
3. الأسئلة مناسبة تماماً لمستوى الصف
4. كل سؤال يطبق فلسفة الجذر المخصص له
5. الخيارات متقاربة ومنطقية (تجنب الخيارات السخيفة)
6. خيار واحد فقط صحيح بشكل قاطع
7. تجنب صيغ النفي في الأسئلة
8. نوّع في أساليب طرح الأسئلة

✅ الصيغة المطلوبة (JSON دقيق):
{
PROMPT;

        if ($includePassage) {
            $prompt .= <<<PROMPT
  "passage_title": "عنوان جذاب للنص",
  "passage": "النص الكامل هنا...",
PROMPT;
        }

        $prompt .= <<<PROMPT
  "questions": [
    {
      "question": "نص السؤال الواضح",
      "root_type": "jawhar أو zihn أو waslat أو roaya",
      "depth_level": 1 أو 2 أو 3,
      "options": ["الخيار الأول", "الخيار الثاني", "الخيار الثالث", "الخيار الرابع"],
      "correct_answer": "النص الكامل والدقيق للإجابة الصحيحة من الخيارات"
    }
  ]
}

⚡ تذكير نهائي: أنشئ بالضبط $totalQuestions سؤال حسب التوزيع المحدد أعلاه.
PROMPT;

        return $prompt;
    }

    /**
     * Build prompt for passage-based questions
     */
    private function buildPassageBasedPrompt(
        string $passage,
        string $passageTitle,
        string $subject,
        string $gradeLevel,
        array $rootSettings
    ): string {
        $subjectNames = [
            'arabic' => 'اللغة العربية',
            'english' => 'اللغة الإنجليزية',
            'hebrew' => 'اللغة العبرية'
        ];

        $subjectName = $subjectNames[$subject] ?? $subject;

        $prompt = <<<PROMPT
أنت خبير تعليمي متخصص في نموذج جُذور. أنشئ أسئلة تعليمية مبنية على النص التالي:

📖 النص المعطى:
العنوان: $passageTitle
---
$passage
---

📚 معلومات الاختبار:
- المادة: $subjectName
- الصف: $gradeLevel

🌱 نموذج جُذور التعليمي:
• جَوهر 🎯: أسئلة عن المعلومات الأساسية في النص
• ذِهن 🧠: أسئلة عن كيفية حدوث الأشياء المذكورة في النص
• وَصلات 🔗: أسئلة عن العلاقات بين عناصر النص
• رُؤية 👁️: أسئلة عن تطبيق ما تعلمناه من النص

⚠️ قواعد صارمة:
1. كل سؤال يجب أن يكون إجابته موجودة في النص
2. لا تفترض معلومات غير مذكورة
3. اقتبس من النص عند الضرورة
4. تأكد أن الإجابة الصحيحة واضحة من النص

PROMPT;

        // Add questions requirements
        $prompt .= "\n📝 الأسئلة المطلوبة:\n";
        $totalQuestions = 0;

        foreach ($rootSettings as $rootKey => $levels) {
            if (!is_array($levels)) continue;
            
            foreach ($levels as $level) {
                if (isset($level['count']) && $level['count'] > 0) {
                    $rootName = $this->getRootName($rootKey);
                    $depth = $level['depth'] ?? 1;
                    $count = $level['count'];
                    $totalQuestions += $count;
                    $prompt .= "• $rootName (مستوى $depth): $count أسئلة\n";
                }
            }
        }

        // Add format instructions
        $prompt .= <<<PROMPT

✅ الصيغة المطلوبة (JSON):
{
  "passage_title": "$passageTitle",
  "passage": "$passage",
  "questions": [
    {
      "question": "سؤال مرتبط مباشرة بالنص",
      "root_type": "jawhar أو zihn أو waslat أو roaya",
      "depth_level": 1 أو 2 أو 3,
      "options": ["خيار 1", "خيار 2", "خيار 3", "خيار 4"],
      "correct_answer": "الإجابة الصحيحة من الخيارات"
    }
  ]
}

⚡ أنشئ $totalQuestions سؤال بالضبط، كلها مبنية على النص المعطى.
PROMPT;

        return $prompt;
    }

    /**
     * Build report generation prompt
     */
    private function buildReportPrompt(Result $result, Quiz $quiz): string
    {
        $scores = $result->scores;
        $userName = $result->user->name ?? 'الطالب';
        $correctAnswers = $result->answers->where('is_correct', true)->count();
        $totalAnswers = $result->answers->count();

        $prompt = <<<PROMPT
أنشئ تقريراً تعليمياً مفصلاً وإيجابياً لنتائج اختبار جُذور.

📊 بيانات الاختبار:
- الاختبار: {$quiz->title}
- المادة: {$quiz->subject}
- الصف: {$quiz->grade_level}
- الطالب: $userName
- النتيجة: {$result->total_score}% ($correctAnswers/$totalAnswers صحيح)

🌱 أداء الجذور:
- جَوهر: {$scores['jawhar']}%
- ذِهن: {$scores['zihn']}%
- وَصلات: {$scores['waslat']}%
- رُؤية: {$scores['roaya']}%

📝 المطلوب في التقرير:
1. افتتاحية إيجابية ومحفزة
2. تحليل مفصل لكل جذر:
   - نقاط القوة (احتفل بالإنجازات)
   - فرص النمو (بصياغة إيجابية)
   - نصائح عملية محددة
3. خطة تطوير واضحة (3-5 خطوات)
4. رسالة ختامية ملهمة

💡 استخدم:
- لغة إيجابية ومشجعة
- أمثلة ملموسة
- نصائح قابلة للتطبيق
- تركيز على النمو والتطور
PROMPT;

        return $prompt;
    }

    /**
     * Parse and validate quiz response
     */
    private function parseQuizResponse(string $response): array
    {
        // Extract JSON from response
        $jsonMatch = [];
        if (!preg_match('/\{.*\}/s', $response, $jsonMatch)) {
            Log::error('No JSON found in Claude response', [
                'response_preview' => substr($response, 0, 500)
            ]);
            throw new Exception('فشل في معالجة الاستجابة. الرجاء المحاولة مرة أخرى.');
        }

        // Decode JSON
        $data = json_decode($jsonMatch[0], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON parsing error', [
                'error' => json_last_error_msg(),
                'json_preview' => substr($jsonMatch[0], 0, 500)
            ]);
            throw new Exception('خطأ في تحليل البيانات المولدة.');
        }

        // Validate structure
        if (!isset($data['questions']) || !is_array($data['questions'])) {
            throw new Exception('لم يتم توليد الأسئلة بالشكل الصحيح.');
        }

        // Validate each question
        $validQuestions = [];
        foreach ($data['questions'] as $index => $question) {
            if ($this->isValidQuestion($question)) {
                $validQuestions[] = $this->normalizeQuestion($question);
            } else {
                Log::warning('Invalid question skipped', [
                    'index' => $index,
                    'question' => $question
                ]);
            }
        }

        if (empty($validQuestions)) {
            throw new Exception('لم يتم توليد أي أسئلة صالحة.');
        }

        $data['questions'] = $validQuestions;
        return $data;
    }

    /**
     * Validate question structure
     */
    private function isValidQuestion(array $question): bool
    {
        // Check required fields
        if (!isset($question['question'], $question['root_type'], 
                   $question['depth_level'], $question['options'], 
                   $question['correct_answer'])) {
            return false;
        }

        // Validate types
        if (!is_string($question['question']) || 
            !is_array($question['options']) || 
            count($question['options']) < 2) {
            return false;
        }

        // Validate root type
        if (!in_array($question['root_type'], ['jawhar', 'zihn', 'waslat', 'roaya'])) {
            return false;
        }

        // Validate depth level
        if (!in_array($question['depth_level'], [1, 2, 3], true)) {
            return false;
        }

        // Validate correct answer exists in options
        if (!in_array($question['correct_answer'], $question['options'])) {
            return false;
        }

        return true;
    }

    /**
     * Normalize question data
     */
    private function normalizeQuestion(array $question): array
    {
        return [
            'question' => trim($question['question']),
            'root_type' => $question['root_type'],
            'depth_level' => (int) $question['depth_level'],
            'options' => array_values(array_map('trim', $question['options'])),
            'correct_answer' => trim($question['correct_answer'])
        ];
    }

    /**
     * Format report response
     */
    private function formatReportResponse(string $response): string
    {
        // Remove code blocks
        $response = preg_replace('/```[\s\S]*?```/', '', $response);
        
        // Convert markdown to HTML
        $response = preg_replace('/^### (.+)$/m', '<h3 class="text-xl font-bold mb-2 mt-4">$1</h3>', $response);
        $response = preg_replace('/^## (.+)$/m', '<h2 class="text-2xl font-bold mb-3 mt-6">$1</h2>', $response);
        $response = preg_replace('/^# (.+)$/m', '<h1 class="text-3xl font-bold mb-4">$1</h1>', $response);
        
        // Convert bold text
        $response = preg_replace('/\*\*(.+?)\*\*/', '<strong class="font-bold text-purple-600">$1</strong>', $response);
        
        // Convert lists
        $response = preg_replace('/^- (.+)$/m', '<li class="mr-6 mb-1">$1</li>', $response);
        $response = preg_replace('/(<li.*<\/li>)+/s', '<ul class="list-disc mr-8 mb-4">$0</ul>', $response);
        
        // Add paragraph tags
        $paragraphs = explode("\n\n", trim($response));
        $formatted = [];
        foreach ($paragraphs as $paragraph) {
            if (!preg_match('/^<[h|u|o]/i', $paragraph)) {
                $paragraph = '<p class="mb-4 leading-relaxed">' . $paragraph . '</p>';
            }
            $formatted[] = $paragraph;
        }
        
        return implode("\n", $formatted);
    }

    /**
     * Get Arabic root name
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
     * Get user-friendly error message
     */
    private function getErrorMessage(int $statusCode, string $body): string
    {
        switch ($statusCode) {
            case 401:
                return 'مفتاح API غير صحيح. تحقق من إعدادات Claude API.';
            case 403:
                return 'ليس لديك صلاحية للوصول لخدمة Claude.';
            case 429:
                return 'تم تجاوز حد الطلبات. الرجاء المحاولة بعد قليل.';
            case 500:
            case 502:
            case 503:
                return 'خدمة Claude غير متاحة حالياً. الرجاء المحاولة لاحقاً.';
            default:
                Log::error('Unexpected API error', [
                    'status' => $statusCode,
                    'body' => $body
                ]);
                return 'حدث خطأ غير متوقع. الرجاء المحاولة مرة أخرى.';
        }
    }

    /**
     * Get connection error message
     */
    private function getConnectionErrorMessage(GuzzleException $e): string
    {
        if ($e->getCode() === 0) {
            return 'لا يمكن الاتصال بخدمة Claude. تحقق من اتصال الإنترنت.';
        }
        
        if (strpos($e->getMessage(), 'SSL') !== false) {
            return 'خطأ في شهادة الأمان. تواصل مع الدعم الفني.';
        }
        
        if (strpos($e->getMessage(), 'timeout') !== false) {
            return 'انتهت مهلة الاتصال. الرجاء المحاولة مرة أخرى.';
        }
        
        return 'فشل الاتصال بخدمة الذكاء الاصطناعي. الرجاء المحاولة لاحقاً.';
    /**
     * Test the Claude API connection
     */
    public function testConnection(): array
    {
        try {
            $testPrompt = "قل مرحباً باللغة العربية";
            
            Log::info('Testing Claude API connection', [
                'environment' => app()->environment(),
                'ssl_verify' => $this->client->getConfig('verify'),
                'api_key_length' => strlen($this->config['api_key']),
                'model' => $this->config['model']
            ]);
            
            $response = $this->sendRequest($testPrompt, 100);
            
            return [
                'success' => true,
                'message' => 'الاتصال بخدمة Claude يعمل بشكل صحيح',
                'response' => $response,
                'config' => [
                    'environment' => app()->environment(),
                    'ssl_verify' => $this->client->getConfig('verify'),
                    'model' => $this->config['model']
                ]
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'فشل الاتصال بخدمة Claude',
                'error' => $e->getMessage(),
                'config' => [
                    'environment' => app()->environment(),
                    'ssl_verify' => $this->client->getConfig('verify'),
                    'model' => $this->config['model']
                ]
            ];
        }
    }
}