<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClaudeService
{
    private $apiKey;
    private $baseUrl = 'https://api.anthropic.com/v1';
    private $model = 'claude-3-5-sonnet-20241022';
    private $maxTokens = 4000;
    private $temperature = 0.5;

    public function __construct()
    {
        $this->apiKey = config('services.claude.api_key');

        if (!$this->apiKey) {
            throw new \Exception('Claude API key not configured');
        }
    }

    /**
     * Generate educational text
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

            // Enhanced validation and filtering
            if (isset($result['questions'])) {
                $result['questions'] = $this->filterSimilarQuestions($result['questions']);
                $result['questions'] = $this->validateQuestionQuality($result['questions']);
            }

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
     * Generate questions from provided text with enhanced coverage
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

        // Split text into segments for better coverage
        $textSegments = $this->splitTextIntoSegments($text);

        $allQuestions = [];

        // Generate questions for each root type separately to ensure distinctness
        foreach ($roots as $rootType => $levels) {
            foreach ($levels as $level => $count) {
                for ($i = 0; $i < $count; $i++) {
                    $prompt = $this->buildRootSpecificPrompt(
                        $text,
                        $subject,
                        $gradeLevel,
                        $rootType,
                        (int) $level,
                        $textSegments
                    );

                    try {
                        $response = $this->sendRequest($prompt, [
                            'temperature' => 0.4, // Lower temperature for more focused questions
                            'max_tokens' => 800
                        ]);

                        $question = $this->parseQuestionResponse($response, $rootType, (int) $level);
                        if ($question && $this->isQuestionValid($question)) {
                            $allQuestions[] = $question;
                        }
                    } catch (\Exception $e) {
                        Log::warning('Failed to generate question', [
                            'root' => $rootType,
                            'level' => $level,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }
        }

        // Filter similar questions and validate quality
        $filteredQuestions = $this->filterSimilarQuestions($allQuestions);
        $validatedQuestions = $this->validateQuestionQuality($filteredQuestions);

        $this->trackUsage('questions_from_text', count($validatedQuestions));

        Log::info('Questions generated successfully', [
            'initial_count' => count($allQuestions),
            'after_filtering' => count($filteredQuestions),
            'final_count' => count($validatedQuestions)
        ]);

        return $validatedQuestions;
    }

    /**
     * Generate smart suggestions for question improvement
     */
    public function generateQuestionSuggestions(
        string $questionText,
        array $options,
        string $correctAnswer,
        string $rootType,
        int $depthLevel,
        string $context = ''
    ): array {
        Log::info('Generating question suggestions', [
            'root_type' => $rootType,
            'depth_level' => $depthLevel
        ]);

        $prompt = $this->buildSuggestionPrompt(
            $questionText,
            $options,
            $correctAnswer,
            $rootType,
            $depthLevel,
            $context
        );

        try {
            $response = $this->sendRequest($prompt, [
                'temperature' => 0.7,
                'max_tokens' => 1000
            ]);

            $suggestions = $this->parseSuggestionsResponse($response);
            $this->trackUsage('question_suggestions', 1);

            Log::info('Question suggestions generated successfully', [
                'suggestions_count' => count($suggestions)
            ]);

            return $suggestions;
        } catch (\Exception $e) {
            Log::error('Question suggestions failed', [
                'error' => $e->getMessage()
            ]);
            throw new \Exception('فشل توليد الاقتراحات: ' . $e->getMessage());
        }
    }

    /**
     * Build root-specific prompt for precise question generation
     */
    private function buildRootSpecificPrompt(
        string $text,
        string $subject,
        int $gradeLevel,
        string $rootType,
        int $depthLevel,
        array $textSegments
    ): string {
        $subjectName = $this->getSubjectName($subject);
        $rootDefinition = $this->getRootDefinition($rootType);
        $levelDescription = $this->getDepthLevelDescription($depthLevel);

        // Select a random text segment to ensure coverage
        $selectedSegment = $textSegments[array_rand($textSegments)];

        $prompt = <<<PROMPT
أنت خبير تعليمي متخصص في نموذج جُذور. مهمتك إنشاء سؤال واحد فقط من نوع {$rootDefinition['name']}.

النص المرجعي:
{$text}

التركيز على هذا الجزء (لضمان التغطية):
{$selectedSegment}

المعلومات:
- المادة: {$subjectName}
- الصف: {$gradeLevel}
- نوع الجذر: {$rootDefinition['name']} ({$rootDefinition['focus']})
- المستوى: {$depthLevel} ({$levelDescription})

{$rootDefinition['detailed_instructions']}

قواعد صارمة:
1. سؤال واحد فقط يركز على {$rootDefinition['focus']}
2. يجب أن يكون مختلفاً تماماً عن أسئلة الأنواع الأخرى
3. أربعة خيارات مختلفة تماماً
4. ممنوع "جميع ما سبق" أو "كل ما ذكر"
5. الإجابة الصحيحة واضحة من النص

أرجع JSON بالشكل التالي فقط:
{
    "question": "نص السؤال",
    "options": ["خيار1", "خيار2", "خيار3", "خيار4"],
    "correct_answer": "الإجابة الصحيحة",
    "root_type": "{$rootType}",
    "depth_level": {$depthLevel}
}
PROMPT;

        return $prompt;
    }

    /**
     * Build suggestion prompt for question improvement
     */
    private function buildSuggestionPrompt(
        string $questionText,
        array $options,
        string $correctAnswer,
        string $rootType,
        int $depthLevel,
        string $context
    ): string {
        $rootDefinition = $this->getRootDefinition($rootType);
        $optionsText = implode(', ', $options);

        $prompt = <<<PROMPT
أنت خبير تعليمي متخصص في تحسين الأسئلة التعليمية لنموذج جُذور.

السؤال الحالي:
النص: {$questionText}
الخيارات: {$optionsText}
الإجابة الصحيحة: {$correctAnswer}
نوع الجذر: {$rootDefinition['name']} ({$rootDefinition['focus']})
المستوى: {$depthLevel}

السياق: {$context}

قدم اقتراحات للتحسين في المجالات التالية:
1. وضوح صياغة السؤال
2. جودة الخيارات المتاحة
3. التأكد من مطابقة السؤال لنوع الجذر
4. تحسين مستوى الصعوبة
5. إضافة عمق أكثر للسؤال

أرجع JSON بالشكل التالي:
{
    "suggestions": [
        {
            "type": "نوع التحسين",
            "current": "الوضع الحالي",
            "suggested": "التحسين المقترح",
            "reason": "سبب التحسين"
        }
    ],
    "improved_question": {
        "question": "السؤال المحسن",
        "options": ["خيار1", "خيار2", "خيار3", "خيار4"],
        "correct_answer": "الإجابة الصحيحة"
    }
}
PROMPT;

        return $prompt;
    }

    /**
     * Get detailed root definition with specific instructions
     */
    private function getRootDefinition(string $rootType): array
    {
        $definitions = [
            'jawhar' => [
                'name' => 'جَوهر (الماهية)',
                'focus' => 'فهم المعاني والتعريفات الأساسية',
                'detailed_instructions' => '
                    أسئلة جَوهر تركز على:
                    - ما معنى هذا المصطلح؟
                    - ما هي الفكرة الأساسية؟
                    - من هي الشخصية الرئيسية؟
                    - ما هو تعريف هذا المفهوم؟
                    - ما طبيعة هذا الشيء؟
                    
                    تجنب الأسئلة التحليلية أو السببية أو التطبيقية.'
            ],
            'zihn' => [
                'name' => 'ذِهن (العقل)',
                'focus' => 'التحليل والتفكير المنطقي',
                'detailed_instructions' => '
                    أسئلة ذِهن تركز على:
                    - لماذا حدث هذا؟
                    - كيف تعمل هذه العملية؟
                    - ما العلاقة بين السبب والنتيجة؟
                    - قارن بين X و Y
                    - حلل هذا الموقف
                    
                    تجنب أسئلة التعريف البسيطة أو التطبيق العملي.'
            ],
            'waslat' => [
                'name' => 'وَصلات (الروابط)',
                'focus' => 'الربط بين المفاهيم والواقع',
                'detailed_instructions' => '
                    أسئلة وَصلات تركز على:
                    - كيف يرتبط هذا بالحياة الواقعية؟
                    - ما العلاقة بين هذا المفهوم ومجالات أخرى؟
                    - كيف يؤثر هذا على المجتمع؟
                    - ما الصلة بين هذا والثقافة؟
                    - كيف يتصل هذا بتجربتك الشخصية؟
                    
                    تجنب الأسئلة النظرية المجردة أو التعريفات البسيطة.'
            ],
            'roaya' => [
                'name' => 'رُؤية (البصيرة)',
                'focus' => 'الإبداع والتطبيق المستقبلي',
                'detailed_instructions' => '
                    أسئلة رُؤية تركز على:
                    - كيف يمكن استخدام هذا في المستقبل؟
                    - ما الحلول الإبداعية لهذه المشكلة؟
                    - كيف يمكن تطوير هذا الفكرة؟
                    - ما النتائج المحتملة لهذا؟
                    - كيف يمكن الاستفادة من هذا عملياً؟
                    
                    تجنب الأسئلة التحليلية أو التعريفية البسيطة.'
            ]
        ];

        return $definitions[$rootType] ?? $definitions['jawhar'];
    }

    /**
     * Get depth level description
     */
    private function getDepthLevelDescription(int $level): string
    {
        $descriptions = [
            1 => 'سطحي - فهم أولي ومباشر',
            2 => 'متوسط - فهم أعمق مع التحليل',
            3 => 'عميق - فهم متقدم مع التطبيق المبتكر'
        ];

        return $descriptions[$level] ?? $descriptions[1];
    }

    /**
     * Split text into segments for better coverage
     */
    private function splitTextIntoSegments(string $text): array
    {
        // Split by paragraphs first
        $paragraphs = preg_split('/\n\s*\n/', $text);

        $segments = [];
        foreach ($paragraphs as $paragraph) {
            $paragraph = trim($paragraph);
            if (strlen($paragraph) > 50) { // Minimum segment length
                // Further split long paragraphs by sentences
                $sentences = preg_split('/[.!?]+/', $paragraph);
                $currentSegment = '';

                foreach ($sentences as $sentence) {
                    $sentence = trim($sentence);
                    if (strlen($sentence) > 20) {
                        if (strlen($currentSegment) + strlen($sentence) > 200) {
                            if (!empty($currentSegment)) {
                                $segments[] = $currentSegment;
                            }
                            $currentSegment = $sentence;
                        } else {
                            $currentSegment .= ($currentSegment ? '. ' : '') . $sentence;
                        }
                    }
                }

                if (!empty($currentSegment)) {
                    $segments[] = $currentSegment;
                }
            }
        }

        // Ensure we have at least one segment
        if (empty($segments)) {
            $segments[] = substr($text, 0, 200);
        }

        return $segments;
    }

    /**
     * Filter similar questions using semantic comparison
     */
    private function filterSimilarQuestions(array $questions): array
    {
        $filtered = [];

        foreach ($questions as $question) {
            $isSimilar = false;

            foreach ($filtered as $existingQuestion) {
                $similarity = $this->calculateQuestionSimilarity(
                    $question['question'],
                    $existingQuestion['question']
                );

                // Also check option similarity
                $optionSimilarity = $this->calculateOptionsSimilarity(
                    $question['options'] ?? [],
                    $existingQuestion['options'] ?? []
                );

                if ($similarity > 0.7 || $optionSimilarity > 0.6) {
                    $isSimilar = true;
                    Log::info('Filtered similar question', [
                        'similarity' => $similarity,
                        'option_similarity' => $optionSimilarity,
                        'new_question' => substr($question['question'], 0, 50),
                        'existing_question' => substr($existingQuestion['question'], 0, 50)
                    ]);
                    break;
                }
            }

            if (!$isSimilar) {
                $filtered[] = $question;
            }
        }

        return $filtered;
    }

    /**
     * Calculate question similarity using simple text comparison
     */
    private function calculateQuestionSimilarity(string $q1, string $q2): float
    {
        // Remove common Arabic question words for better comparison
        $commonWords = ['ما', 'من', 'متى', 'أين', 'كيف', 'لماذا', 'هل', 'أي', 'كم'];

        foreach ($commonWords as $word) {
            $q1 = str_replace($word, '', $q1);
            $q2 = str_replace($word, '', $q2);
        }

        $q1 = preg_replace('/\s+/', ' ', trim($q1));
        $q2 = preg_replace('/\s+/', ' ', trim($q2));

        // Calculate word overlap
        $words1 = explode(' ', $q1);
        $words2 = explode(' ', $q2);

        $intersection = count(array_intersect($words1, $words2));
        $union = count(array_unique(array_merge($words1, $words2)));

        return $union > 0 ? $intersection / $union : 0;
    }

    /**
     * Calculate options similarity
     */
    private function calculateOptionsSimilarity(array $options1, array $options2): float
    {
        if (empty($options1) || empty($options2)) {
            return 0;
        }

        $similarities = [];
        foreach ($options1 as $opt1) {
            foreach ($options2 as $opt2) {
                $similarities[] = $this->calculateQuestionSimilarity($opt1, $opt2);
            }
        }

        return count($similarities) > 0 ? max($similarities) : 0;
    }

    /**
     * Validate question quality
     */
    private function validateQuestionQuality(array $questions): array
    {
        $validated = [];

        foreach ($questions as $question) {
            if ($this->isQuestionValid($question)) {
                $validated[] = $question;
            } else {
                Log::warning('Invalid question filtered out', [
                    'question' => substr($question['question'] ?? '', 0, 50),
                    'reason' => 'Quality validation failed'
                ]);
            }
        }

        return $validated;
    }

    /**
     * Check if question meets quality standards
     */
    private function isQuestionValid(array $question): bool
    {
        // Check required fields
        if (empty($question['question']) || empty($question['options']) || empty($question['correct_answer'])) {
            return false;
        }

        // Check options count
        if (count($question['options']) !== 4) {
            return false;
        }

        // Check if correct answer exists in options
        if (!in_array($question['correct_answer'], $question['options'])) {
            return false;
        }

        // Check for forbidden options
        $forbiddenOptions = ['جميع ما سبق', 'كل ما ذكر', 'لا شيء مما ذكر', 'أ و ب', 'ب و ج'];
        foreach ($question['options'] as $option) {
            foreach ($forbiddenOptions as $forbidden) {
                if (stripos($option, $forbidden) !== false) {
                    return false;
                }
            }
        }

        // Check for duplicate options
        if (count($question['options']) !== count(array_unique($question['options']))) {
            return false;
        }

        return true;
    }

    /**
     * Parse single question response
     */
    private function parseQuestionResponse(array $response, string $expectedRoot, int $expectedLevel): ?array
    {
        try {
            $content = $this->extractContent($response);
            $jsonStart = strpos($content, '{');

            if ($jsonStart === false) {
                return null;
            }

            $potentialJson = substr($content, $jsonStart);
            $data = json_decode($potentialJson, true);

            if (json_last_error() === JSON_ERROR_NONE && isset($data['question'])) {
                // Ensure correct root type and depth level
                $data['root_type'] = $expectedRoot;
                $data['depth_level'] = $expectedLevel;

                return $data;
            }
        } catch (\Exception $e) {
            Log::warning('Failed to parse question response', ['error' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Parse suggestions response
     */
    private function parseSuggestionsResponse(array $response): array
    {
        try {
            $content = $this->extractContent($response);
            $jsonStart = strpos($content, '{');

            if ($jsonStart === false) {
                return [];
            }

            $potentialJson = substr($content, $jsonStart);
            $data = json_decode($potentialJson, true);

            if (json_last_error() === JSON_ERROR_NONE && isset($data['suggestions'])) {
                return $data;
            }
        } catch (\Exception $e) {
            Log::warning('Failed to parse suggestions response', ['error' => $e->getMessage()]);
        }

        return [];
    }

    // ... [Keep all existing helper methods like buildTextGenerationPrompt, buildJuzoorQuizPrompt, 
    // sendRequest, extractContent, parseCompleteQuizResponse, getSubjectName, etc.]

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
            'temperature' => $overrides['temperature'] ?? $this->temperature
        ];

        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json'
        ])->timeout(60)->post($this->baseUrl . '/messages', $payload);

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

    /**
     * Extract text content from Claude response
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
     * Parse complete quiz response
     */
    private function parseCompleteQuizResponse(array $response): array
    {
        $content = $this->extractContent($response);

        // Find JSON content
        $jsonStart = strpos($content, '{');
        if ($jsonStart === false) {
            throw new \Exception('No valid JSON found in AI response');
        }

        $potentialJson = substr($content, $jsonStart);

        // Find matching closing brace
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
            $data = json_decode($jsonString, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return [
                    'passage' => $data['passage'] ?? null,
                    'passage_title' => $data['passage_title'] ?? null,
                    'questions' => $data['questions'] ?? []
                ];
            }
        }

        throw new \Exception('Failed to parse complete quiz from AI response');
    }

    /**
     * Track AI usage
     */
    private function trackUsage(string $operation, int $tokensUsed): void
    {
        try {
            // Simple logging instead of database tracking
            Log::info('AI Usage', [
                'user_id' => auth()->check() ? auth()->id() : null,
                'operation' => $operation,
                'tokens_used' => $tokensUsed,
                'model' => $this->model
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to track AI usage', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Helper methods
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

    /**
     * Test API connection
     */
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

    /**
     * Generate general AI completion
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