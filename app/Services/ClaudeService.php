<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ClaudeService
{
    protected $apiKey;
    protected $model;
    protected $maxTokens;
    protected $temperature;

    public function __construct()
    {
        $this->apiKey = config('services.claude.key');
        $this->model = config('services.claude.model', 'claude-3-sonnet-20240229');
        $this->maxTokens = config('services.claude.max_tokens', 4000);
        $this->temperature = config('services.claude.temperature', 0.7);
    }

    /**
     * Generate completion using Claude API
     */
    public function generateCompletion($prompt, $options = [])
    {
        $maxTokens = $options['max_tokens'] ?? $this->maxTokens;
        $temperature = $options['temperature'] ?? $this->temperature;

        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->timeout(60)->post('https://api.anthropic.com/v1/messages', [
                        'model' => $this->model,
                        'max_tokens' => $maxTokens,
                        'temperature' => $temperature,
                        'messages' => [
                            ['role' => 'user', 'content' => $prompt]
                        ]
                    ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'content' => $data['content'][0]['text'] ?? ''
                ];
            }

            Log::error('Claude API error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to generate completion'
            ];

        } catch (\Exception $e) {
            Log::error('Claude API exception', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate passage for quiz
     */
    public function generatePassage($subject, $gradeLevel, $topic, $length = 'medium')
    {
        $wordCounts = [
            'short' => '50-100',
            'medium' => '150-250',
            'long' => '300-500'
        ];

        $prompt = "أنت خبير تربوي متخصص في إنشاء نصوص تعليمية للطلاب. قم بكتابة نص تعليمي حول موضوع '{$topic}' مناسب للصف {$gradeLevel} في مادة {$subject}.\n\n";
        $prompt .= "المتطلبات:\n";
        $prompt .= "- طول النص: {$wordCounts[$length]} كلمة\n";
        $prompt .= "- لغة واضحة ومناسبة للفئة العمرية\n";
        $prompt .= "- معلومات دقيقة وتعليمية\n";
        $prompt .= "- النص يجب أن يكون غنياً بالمعلومات لبناء أسئلة متنوعة عليه\n";
        $prompt .= "- استخدم أسلوباً جذاباً ومشوقاً للطلاب\n\n";
        $prompt .= "قم بإرجاع النص فقط دون أي تعليقات إضافية.";

        $response = $this->generateCompletion($prompt);

        if ($response['success']) {
            return [
                'success' => true,
                'content' => $response['content'],
                'title' => $topic
            ];
        }

        return $response;
    }

    /**
     * Generate questions from existing passage
     */
    public function generateQuestionsFromPassage($passage, $subject, $gradeLevel, $rootDistribution)
    {
        $prompt = $this->buildQuestionGenerationPrompt($passage, $subject, $gradeLevel, $rootDistribution);

        $response = $this->generateCompletion($prompt, [
            'max_tokens' => 4000,
            'temperature' => 0.7
        ]);

        if ($response['success']) {
            $questions = $this->parseQuestionsFromResponse($response['content']);
            return [
                'success' => true,
                'questions' => $questions
            ];
        }

        return $response;
    }

    /**
     * Build prompt for question generation
     */
    private function buildQuestionGenerationPrompt($passage, $subject, $gradeLevel, $rootDistribution)
    {
        $prompt = "أنت خبير تربوي متخصص في نموذج جُذور التعليمي. بناءً على النص التالي، قم بإنشاء أسئلة اختيار من متعدد.\n\n";
        $prompt .= "النص:\n{$passage}\n\n";
        $prompt .= "معلومات الاختبار:\n";
        $prompt .= "- المادة: {$subject}\n";
        $prompt .= "- الصف: {$gradeLevel}\n\n";

        $prompt .= "توزيع الأسئلة المطلوب:\n";
        foreach ($rootDistribution as $root => $levels) {
            $rootName = ['jawhar' => 'جَوهر', 'zihn' => 'ذِهن', 'waslat' => 'وَصلات', 'roaya' => 'رُؤية'][$root] ?? $root;
            foreach ($levels as $level) {
                $prompt .= "- {$rootName} مستوى {$level['depth']}: {$level['count']} أسئلة\n";
            }
        }

        $prompt .= "\nقم بإنشاء الأسئلة بصيغة JSON بالشكل التالي:\n";
        $prompt .= '```json
[
    {
        "question": "نص السؤال",
        "root_type": "jawhar|zihn|waslat|roaya",
        "depth_level": 1|2|3,
        "options": ["خيار 1", "خيار 2", "خيار 3", "خيار 4"],
        "correct_answer": "الإجابة الصحيحة"
    }
]
```';

        return $prompt;
    }

    /**
     * Parse questions from AI response
     */
    private function parseQuestionsFromResponse($content)
    {
        // Extract JSON from response
        if (preg_match('/```json\s*(.*?)\s*```/s', $content, $matches)) {
            $jsonString = $matches[1];
        } else if (preg_match('/\[.*\]/s', $content, $matches)) {
            $jsonString = $matches[0];
        } else {
            // Try to parse the entire content as JSON
            $jsonString = $content;
        }

        try {
            $questions = json_decode($jsonString, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($questions)) {
                return $questions;
            }
        } catch (\Exception $e) {
            Log::error('Failed to parse questions JSON', ['error' => $e->getMessage()]);
        }

        return [];
    }

    /**
     * Test connection to Claude API
     */
    public function testConnection()
    {
        try {
            $response = $this->generateCompletion('مرحبا', [
                'max_tokens' => 10,
                'temperature' => 0
            ]);

            return [
                'success' => $response['success'],
                'message' => $response['success'] ? 'Claude API connected successfully' : 'Failed to connect to Claude API'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generate complete Juzoor quiz
     */
    public function generateJuzoorQuiz($subject, $gradeLevel, $topic, $settings, $includePassage = false, $passageTopic = null)
    {
        $prompt = $this->buildJuzoorQuizPrompt($subject, $gradeLevel, $topic, $settings, $includePassage, $passageTopic);

        $response = $this->generateCompletion($prompt, [
            'max_tokens' => 4000,
            'temperature' => 0.7
        ]);

        if ($response['success']) {
            return $this->parseJuzoorQuizResponse($response['content']);
        }

        throw new \Exception('Failed to generate quiz: ' . ($response['error'] ?? 'Unknown error'));
    }

    /**
     * Build prompt for Juzoor quiz generation
     */
    private function buildJuzoorQuizPrompt($subject, $gradeLevel, $topic, $settings, $includePassage, $passageTopic)
    {
        $prompt = "أنت خبير تربوي متخصص في نموذج جُذور التعليمي. قم بإنشاء اختبار تعليمي شامل.\n\n";

        $prompt .= "معلومات الاختبار:\n";
        $prompt .= "- المادة: {$subject}\n";
        $prompt .= "- الصف: {$gradeLevel}\n";
        $prompt .= "- الموضوع: {$topic}\n\n";

        if ($includePassage) {
            $prompt .= "قم أولاً بكتابة نص قراءة حول موضوع: " . ($passageTopic ?? $topic) . "\n";
            $prompt .= "النص يجب أن يكون مناسباً للصف المحدد وغنياً بالمعلومات.\n\n";
        }

        $prompt .= "ثم قم بإنشاء أسئلة اختيار من متعدد بناءً على التوزيع التالي:\n";

        foreach ($settings as $root => $levels) {
            $rootName = ['jawhar' => 'جَوهر', 'zihn' => 'ذِهن', 'waslat' => 'وَصلات', 'roaya' => 'رُؤية'][$root] ?? $root;
            foreach ($levels as $level) {
                $prompt .= "- {$rootName} مستوى {$level['depth']}: {$level['count']} أسئلة\n";
            }
        }

        $prompt .= "\nأرجع النتيجة بصيغة JSON كالتالي:\n";
        $prompt .= '```json
{
    "passage": "نص القراءة (إذا طُلب)",
    "passage_title": "عنوان النص",
    "questions": [
        {
            "question": "نص السؤال",
            "root_type": "jawhar|zihn|waslat|roaya",
            "depth_level": 1|2|3,
            "options": ["خيار 1", "خيار 2", "خيار 3", "خيار 4"],
            "correct_answer": "الإجابة الصحيحة"
        }
    ]
}
```';

        return $prompt;
    }

    /**
     * Parse Juzoor quiz response
     */
    private function parseJuzoorQuizResponse($content)
    {
        // Extract JSON from response
        if (preg_match('/```json\s*(.*?)\s*```/s', $content, $matches)) {
            $jsonString = $matches[1];
        } else {
            $jsonString = $content;
        }

        try {
            $data = json_decode($jsonString, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $data;
            }
        } catch (\Exception $e) {
            Log::error('Failed to parse quiz JSON', ['error' => $e->getMessage()]);
        }

        // Return default structure if parsing fails
        return [
            'passage' => null,
            'passage_title' => null,
            'questions' => []
        ];
    }
}