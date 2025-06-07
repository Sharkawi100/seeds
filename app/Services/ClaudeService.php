<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Quiz;
use App\Models\Question;

class ClaudeService  // MAKE SURE THIS SAYS ClaudeService NOT QuizController
{
    private string $apiKey;
    private string $model;
    private int $maxTokens;
    private float $temperature;
    private bool $cacheEnabled;
    private array $httpOptions;

    public function __construct()
    {
        $this->apiKey = config('services.claude.key');
        $this->model = config('services.claude.model', 'claude-3-5-sonnet-20241022');
        $this->maxTokens = (int) config('services.claude.max_tokens', 4000);
        $this->temperature = (float) config('services.claude.temperature', 0.7);
        $this->cacheEnabled = (bool) config('services.claude.cache_enabled', true);

        // INCREASED TIMEOUT SETTINGS HERE
        $this->httpOptions = [
            'timeout' => (int) config('services.claude.timeout', 120),  // Use config or default to 120
            'connect_timeout' => 30,    // Increased from 10 to 30 seconds
            'headers' => [
                'anthropic-version' => '2023-06-01',
                'anthropic-beta' => 'messages-2023-12-15',
                'x-api-key' => $this->apiKey,
                'content-type' => 'application/json',
            ]
        ];

        $this->configureProxy();

        // Debug logging for initialization
        Log::info('ClaudeService initialized', [
            'model' => $this->model,
            'timeout' => $this->httpOptions['timeout'],
            'api_key_exists' => !empty($this->apiKey)
        ]);
    }

    /**
     * Configure proxy settings if available
     */
    private function configureProxy(): void
    {
        $httpProxy = env('HTTP_PROXY');
        $httpsProxy = env('HTTPS_PROXY', $httpProxy);

        if ($httpProxy || $httpsProxy) {
            $this->httpOptions['proxy'] = [
                'http' => $httpProxy,
                'https' => $httpsProxy,
            ];
        }
    }

    /**
     * Generate educational text/passage
     */
    public function generateEducationalText(
        string $subject,
        int $gradeLevel,
        string $topic,
        string $textType,
        string $length
    ): string {
        Log::info('Generating educational text', [
            'subject' => $subject,
            'grade' => $gradeLevel,
            'topic' => $topic
        ]);

        $prompt = $this->buildTextGenerationPrompt($subject, $gradeLevel, $topic, $textType, $length);

        try {
            $response = $this->sendRequest($prompt, [
                'temperature' => 0.8,
                'max_tokens' => $this->getLengthTokens($length)
            ]);

            $text = $this->extractContent($response);
            $this->trackUsage('text_generation', 1);

            Log::info('Educational text generated successfully', ['length' => strlen($text)]);
            return $text;
        } catch (\Exception $e) {
            Log::error('Text generation failed', [
                'error' => $e->getMessage(),
                'params' => compact('subject', 'gradeLevel', 'topic', 'textType', 'length')
            ]);
            throw new \Exception('فشل توليد النص: ' . $e->getMessage());
        }
    }

    /**
     * Generate questions from provided text
     */
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
                'temperature' => 0.5,
                'max_tokens' => 3000
            ]);

            $questions = $this->parseQuizResponse($response);
            $validatedQuestions = $this->validateQuestionDistribution($questions, $roots);

            $this->trackUsage('questions_from_text', count($validatedQuestions));

            Log::info('Questions generated successfully', ['count' => count($validatedQuestions)]);
            return $validatedQuestions;
        } catch (\Exception $e) {
            Log::error('Question generation from text failed', [
                'error' => $e->getMessage(),
                'text_length' => strlen($text)
            ]);
            throw new \Exception('فشل توليد الأسئلة من النص: ' . $e->getMessage());
        }
    }

    /**
     * Generate complete Juzoor quiz
     */
    public function generateJuzoorQuiz(
        string $subject,
        int $gradeLevel,
        string $topic,
        array $settings,
        bool $includePassage = false,
        ?string $passageTopic = null
    ): array {
        Log::info('Generating Juzoor quiz', [
            'subject' => $subject,
            'grade' => $gradeLevel,
            'topic' => $topic,
            'settings' => $settings,
            'include_passage' => $includePassage
        ]);

        $prompt = $this->buildJuzoorQuizPrompt(
            $subject,
            $gradeLevel,
            $topic,
            $settings,
            $includePassage,
            $passageTopic
        );

        try {
            $response = $this->sendRequest($prompt, [
                'temperature' => 0.6,
                'max_tokens' => 4000
            ]);

            $result = $this->parseCompleteQuizResponse($response);
            $this->trackUsage('complete_quiz_generation', count($result['questions']));

            Log::info('Juzoor quiz generated successfully', [
                'questions_count' => count($result['questions']),
                'has_passage' => !empty($result['passage'])
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Juzoor quiz generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'params' => compact('subject', 'gradeLevel', 'topic')
            ]);
            throw new \Exception('فشل توليد الاختبار: ' . $e->getMessage());
        }
    }

    /**
     * Build prompt for text generation
     */
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

        $prompt = <<<PROMPT
أنت كاتب تربوي متخصص في إنشاء نصوص تعليمية للطلاب.

المطلوب: كتابة {$textTypeName} تعليمي
المادة: {$subjectName}
الصف: {$gradeLevel}
الموضوع: {$topic}
الطول: {$lengthDescription}

القواعد:
1. النص يجب أن يكون مناسباً لمستوى الصف المحدد
2. استخدم لغة واضحة وسليمة
3. اجعل النص ممتعاً وجذاباً للطلاب
4. تضمين معلومات تعليمية قيمة
5. النص يجب أن يكون متماسكاً ومترابطاً
6. مراعاة الفئة العمرية في اختيار المفردات والأسلوب

اكتب النص مباشرة دون مقدمات أو شروحات إضافية.
PROMPT;

        if ($subject === 'arabic') {
            $prompt .= "\n7. استخدم اللغة العربية الفصحى";
        } elseif ($subject === 'english') {
            $prompt .= "\n7. Write the text in English appropriate for the grade level";
        } elseif ($subject === 'hebrew') {
            $prompt .= "\n7. כתוב את הטקסט בעברית המתאימה לרמת הכיתה";
        }

        return $prompt;
    }

    /**
     * Build prompt for generating questions from text
     */
    private function buildQuestionsFromTextPrompt(
        string $text,
        string $subject,
        int $gradeLevel,
        array $roots
    ): string {
        $subjectName = $this->getSubjectName($subject);

        $prompt = <<<PROMPT
    أنت مساعد تعليمي متخصص في إنشاء أسئلة اختبار باستخدام نموذج جُذور التعليمي بناءً على نص معطى.
    
    النص المعطى:
    {$text}
    
    المعلومات الأساسية:
    - المادة: {$subjectName}
    - الصف: {$gradeLevel}
    
    نموذج جُذور يتكون من أربعة أنواع:
    1. جَوهر (jawhar): "ما هو؟" - أسئلة عن التعريفات والحقائق المباشرة من النص
    2. ذِهن (zihn): "كيف يعمل؟" - أسئلة تحليلية عن العمليات والأسباب في النص
    3. وَصلات (waslat): "كيف يرتبط؟" - أسئلة عن العلاقات والروابط بين عناصر النص
    4. رُؤية (roaya): "كيف نستخدمه؟" - أسئلة تطبيقية وإبداعية مبنية على النص
    
    مستويات العمق:
    - المستوى 1: سطحي (أسئلة مباشرة من النص)
    - المستوى 2: متوسط (تحليل وفهم أعمق)
    - المستوى 3: عميق (تفكير نقدي واستنتاج)
    
    المطلوب إنشاء أسئلة بناءً على النص المعطى بالتوزيع التالي:
    PROMPT;

        foreach ($roots as $rootType => $levels) {
            $rootName = $this->getRootName($rootType);
            $prompt .= "\n\n{$rootName} ({$rootType}):";
            foreach ($levels as $level => $count) {
                if ($count > 0) {
                    $prompt .= "\n- المستوى {$level}: {$count} أسئلة";
                }
            }
        }

        $prompt .= <<<PROMPT
    
    قواعد مهمة جداً:
    1. جميع الأسئلة يجب أن تكون مرتبطة مباشرة بالنص المعطى
    2. لا تسأل عن معلومات غير موجودة في النص
    3. كل سؤال يجب أن يكون واضحاً ومحدداً
    4. أربعة خيارات لكل سؤال، خيار واحد صحيح فقط
    5. الخيارات الخاطئة يجب أن تكون منطقية ومعقولة
    6. استخدم EXACTLY الكلمات الإنجليزية للـ root_type: "jawhar", "zihn", "waslat", "roaya"
    7. استخدم EXACTLY الأرقام للـ depth_level: 1, 2, أو 3
    8. ممنوع منعاً باتاً استخدام خيارات مثل "جميع ما سبق" أو "كل ما ذكر" أو "لا شيء مما ذكر" أو "أ و ب"
    9. كل خيار يجب أن يكون مستقلاً ومختلفاً عن الخيارات الأخرى


    
    أرجع الأسئلة بصيغة JSON بالشكل التالي بالضبط:
    {
        "questions": [
            {
                "question": "نص السؤال",
                "options": ["الخيار الأول", "الخيار الثاني", "الخيار الثالث", "الخيار الرابع"],
                "correct_answer": "الإجابة الصحيحة (يجب أن تكون واحدة من الخيارات الأربعة بالضبط)",
                "root_type": "jawhar أو zihn أو waslat أو roaya (استخدم الكلمات الإنجليزية فقط)",
                "depth_level": 1 أو 2 أو 3 (رقم فقط)
            }
        ]
    }
    
    مثال على سؤال صحيح:
    {
        "question": "ما هو لون السجادة المذكورة في النص؟",
        "options": ["أحمر", "أزرق", "أخضر", "ملونة"],
        "correct_answer": "ملونة",
        "root_type": "jawhar",
        "depth_level": 1
    }
    PROMPT;

        return $prompt;
    }

    /**
     * Build prompt for complete Juzoor quiz
     */
    private function buildJuzoorQuizPrompt(
        string $subject,
        int $gradeLevel,
        string $topic,
        array $settings,
        bool $includePassage,
        ?string $passageTopic
    ): string {
        $subjectName = $this->getSubjectName($subject);

        $prompt = <<<PROMPT
أنت مساعد تعليمي متخصص في إنشاء اختبارات تعليمية باستخدام نموذج جُذور.

المعلومات الأساسية:
- المادة: {$subjectName}
- الصف: {$gradeLevel}
- الموضوع: {$topic}
PROMPT;

        if ($includePassage) {
            $passageTopicText = $passageTopic ?: $topic;
            $prompt .= "\n\nأولاً: قم بكتابة نص تعليمي عن '{$passageTopicText}' مناسب للصف {$gradeLevel}";
            $prompt .= "\nالنص يجب أن يكون بطول 150-200 كلمة ويحتوي على معلومات غنية يمكن بناء أسئلة عليها.";
        }

        $prompt .= "\n\nثانياً: قم بإنشاء أسئلة اختيار من متعدد بناءً على ";
        $prompt .= $includePassage ? "النص الذي كتبته" : "الموضوع المحدد";
        $prompt .= " بالتوزيع التالي:\n";

        foreach ($settings as $rootType => $levels) {
            $rootName = $this->getRootName($rootType);
            $prompt .= "\n{$rootName}:";
            foreach ($levels as $levelData) {
                $depth = $levelData['depth'];
                $count = $levelData['count'];
                $prompt .= "\n- المستوى {$depth}: {$count} أسئلة";
            }
        }
        $prompt .= "\n\nقواعد مهمة للأسئلة:";
        $prompt .= "\n- كل سؤال يحتوي على 4 خيارات مختلفة تماماً";
        $prompt .= "\n- ممنوع استخدام خيارات مثل 'جميع ما سبق' أو 'كل ما ذكر'";
        $prompt .= "\n- كل خيار يجب أن يكون مستقلاً وليس مركباً من خيارات أخرى";
        $prompt .= "\n\nأرجع النتيجة بصيغة JSON بالشكل التالي:\n";
        $prompt .= "{\n";
        if ($includePassage) {
            $prompt .= '    "passage": "النص التعليمي",';
            $prompt .= "\n";
            $prompt .= '    "passage_title": "عنوان النص",';
            $prompt .= "\n";
        }
        $prompt .= '    "questions": [';
        $prompt .= "\n        {";
        $prompt .= "\n            \"question\": \"نص السؤال\",";
        $prompt .= "\n            \"options\": [\"خيار1\", \"خيار2\", \"خيار3\", \"خيار4\"],";
        $prompt .= "\n            \"correct_answer\": \"الإجابة الصحيحة\",";
        $prompt .= "\n            \"root_type\": \"jawhar/zihn/waslat/roaya\",";
        $prompt .= "\n            \"depth_level\": 1-3";
        $prompt .= "\n        }";
        $prompt .= "\n    ]";
        $prompt .= "\n}";

        return $prompt;
    }

    /**
     * Send request to Claude API
     */
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
            'temperature' => $overrides['temperature'] ?? $this->temperature,
        ];

        Log::info('Sending request to Claude API', [
            'model' => $payload['model'],
            'max_tokens' => $payload['max_tokens'],
            'prompt_length' => strlen($prompt)
        ]);

        try {
            $response = Http::withOptions($this->httpOptions)
                ->post('https://api.anthropic.com/v1/messages', $payload);

            Log::info('Claude API response received', [
                'status' => $response->status(),
                'successful' => $response->successful()
            ]);

            if (!$response->successful()) {
                $error = $response->json('error.message', 'Unknown error');
                $statusCode = $response->status();
                Log::error('Claude API error', [
                    'status' => $statusCode,
                    'error' => $error,
                    'response' => $response->body()
                ]);
                throw new \Exception("Claude API Error ({$statusCode}): {$error}");
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Claude API request failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Extract content from Claude response
     */
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

    /**
     * Parse quiz response from AI
     */
    private function parseQuizResponse(array $response): array
    {
        $content = $this->extractContent($response);

        Log::info('Parsing quiz response', ['content_length' => strlen($content)]);

        // Extract JSON from the response
        $jsonStart = strpos($content, '{');
        $jsonEnd = strrpos($content, '}');

        if ($jsonStart !== false && $jsonEnd !== false && $jsonEnd > $jsonStart) {
            $jsonString = substr($content, $jsonStart, $jsonEnd - $jsonStart + 1);
            // Clean up common JSON issues from AI responses
            $jsonString = preg_replace('/,\s*]/', ']', $jsonString);  // Remove trailing commas in arrays
            $jsonString = preg_replace('/,\s*}/', '}', $jsonString);  // Remove trailing commas in objects
            $data = json_decode($jsonString, true);

            if (json_last_error() === JSON_ERROR_NONE && isset($data['questions'])) {
                Log::info('Successfully parsed questions', ['count' => count($data['questions'])]);

                // Log first question structure for debugging
                if (!empty($data['questions'])) {
                    Log::info('Sample question structure', [
                        'first_question' => $data['questions'][0]
                    ]);
                }

                return $data['questions'];
            } else {
                Log::error('JSON parsing failed', [
                    'json_error' => json_last_error_msg(),
                    'json_string' => substr($jsonString, 0, 500)
                ]);
            }
        }

        throw new \Exception('Failed to parse quiz questions from AI response');
    }

    /**
     * Parse complete quiz response (with passage)
     */
    private function parseCompleteQuizResponse(array $response): array
    {
        $content = $this->extractContent($response);

        Log::info('Parsing complete quiz response', ['content_length' => strlen($content)]);

        // Extract JSON from the response
        if (preg_match('/\{[\s\S]*\}/s', $content, $matches)) {

            $jsonString = $matches[0];
            // Clean up common JSON issues from AI responses
            $jsonString = preg_replace('/,\s*]/', ']', $jsonString);  // Remove trailing commas in arrays
            $jsonString = preg_replace('/,\s*}/', '}', $jsonString);  // Remove trailing commas in objects
            $data = json_decode($jsonString, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $result = [
                    'passage' => $data['passage'] ?? null,
                    'passage_title' => $data['passage_title'] ?? null,
                    'questions' => $data['questions'] ?? []
                ];

                Log::info('Successfully parsed complete quiz', [
                    'has_passage' => !empty($result['passage']),
                    'questions_count' => count($result['questions'])
                ]);

                return $result;
            } else {
                Log::error('JSON parsing failed for complete quiz', [
                    'json_error' => json_last_error_msg()
                ]);
            }
        }

        throw new \Exception('Failed to parse complete quiz from AI response');
    }

    /**
     * Validate question distribution matches request
     */
    private function validateQuestionDistribution(array $questions, array $requestedRoots): array
    {
        $validated = [];
        $distribution = [];
        $unmatchedQuestions = [];

        // Count requested questions
        foreach ($requestedRoots as $root => $levels) {
            foreach ($levels as $level => $count) {
                $key = "{$root}_{$level}";
                $distribution[$key] = [
                    'requested' => $count,
                    'added' => 0
                ];
            }
        }

        // Process questions
        foreach ($questions as $index => $question) {
            // Normalize root_type (handle variations like Jawhar, JAWHAR, etc.)
            $rootType = strtolower(trim($question['root_type'] ?? ''));

            // Map Arabic or English variations to standard keys
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

            // Ensure depth_level is an integer
            $depthLevel = (int) ($question['depth_level'] ?? 0);

            // Update question with normalized values
            $question['root_type'] = $normalizedRoot;
            $question['depth_level'] = $depthLevel;

            $key = "{$normalizedRoot}_{$depthLevel}";

            if (isset($distribution[$key]) && $distribution[$key]['added'] < $distribution[$key]['requested']) {
                $validated[] = $question;
                $distribution[$key]['added']++;
            } else {
                $unmatchedQuestions[] = [
                    'index' => $index,
                    'root_type' => $question['root_type'],
                    'depth_level' => $question['depth_level'],
                    'key' => $key
                ];
            }
        }

        // Log validation results
        Log::info('Question validation complete', [
            'validated_count' => count($validated),
            'unmatched_count' => count($unmatchedQuestions),
            'distribution_summary' => $distribution
        ]);

        if (!empty($unmatchedQuestions)) {
            Log::warning('Unmatched questions', ['questions' => $unmatchedQuestions]);
        }

        // Log any mismatches in requested vs received
        foreach ($distribution as $key => $counts) {
            if ($counts['added'] < $counts['requested']) {
                Log::warning('Question distribution mismatch', [
                    'type' => $key,
                    'requested' => $counts['requested'],
                    'received' => $counts['added']
                ]);
            }
        }

        // If we have very few validated questions, try to add some unmatched ones
        if (count($validated) < 3 && !empty($questions)) {
            Log::warning('Too few validated questions, adding unvalidated ones', [
                'validated_count' => count($validated),
                'total_questions' => count($questions)
            ]);

            // Add up to 10 questions even if they don't match the distribution
            foreach ($questions as $question) {
                if (count($validated) >= 10)
                    break;

                // Normalize again for unmatched questions
                $rootType = strtolower(trim($question['root_type'] ?? 'jawhar'));
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

                $question['root_type'] = $rootTypeMap[$rootType] ?? 'jawhar';
                $question['depth_level'] = (int) ($question['depth_level'] ?? 1);

                // Check if already added
                $alreadyAdded = false;
                foreach ($validated as $v) {
                    if ($v['question'] === $question['question']) {
                        $alreadyAdded = true;
                        break;
                    }
                }

                if (!$alreadyAdded) {
                    $validated[] = $question;
                }
            }
        }

        return $validated;
    }

    /**
     * Track API usage
     */
    private function trackUsage(string $type, int $count = 1): void
    {
        $dateKey = today()->format('Y-m-d');

        Cache::increment("ai_requests_{$dateKey}");
        Cache::increment("ai_requests_{$type}_{$dateKey}", $count);

        // Store usage in database for long-term tracking
        try {
            DB::table('ai_usage_logs')->insert([
                'type' => $type,
                'model' => $this->model,
                'count' => $count,
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to track AI usage in database', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get tokens based on length
     */
    private function getLengthTokens(string $length): int
    {
        return match ($length) {
            'short' => 500,
            'medium' => 1000,
            'long' => 2000,
            default => 1000
        };
    }

    /**
     * Get length description in Arabic
     */
    private function getLengthDescription(string $length): string
    {
        return match ($length) {
            'short' => 'قصير (50-100 كلمة)',
            'medium' => 'متوسط (150-250 كلمة)',
            'long' => 'طويل (300-500 كلمة)',
            default => 'متوسط'
        };
    }

    /**
     * Get text type name in Arabic
     */
    private function getTextTypeName(string $type): string
    {
        return match ($type) {
            'story' => 'قصة',
            'article' => 'مقال',
            'dialogue' => 'حوار',
            'description' => 'نص وصفي',
            default => 'نص'
        };
    }

    /**
     * Get subject name in Arabic
     */
    private function getSubjectName(string $subject): string
    {
        return match ($subject) {
            'arabic' => 'اللغة العربية',
            'english' => 'اللغة الإنجليزية',
            'hebrew' => 'اللغة العبرية',
            default => $subject
        };
    }

    /**
     * Get root name in Arabic
     */
    private function getRootName(string $root): string
    {
        return match ($root) {
            'jawhar' => 'جَوهر',
            'zihn' => 'ذِهن',
            'waslat' => 'وَصلات',
            'roaya' => 'رُؤية',
            default => $root
        };
    }

    /**
     * Test connection to Claude API
     */
    public function testConnection(): array
    {
        try {
            Log::info('Testing Claude API connection');

            $response = $this->sendRequest('مرحبا، أجب بكلمة "متصل" فقط', [
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

    /**
     * Generate general AI completion
     * Public method for general text generation
     */
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
}