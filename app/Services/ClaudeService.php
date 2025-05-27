<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Models\Result;
use App\Models\Quiz;

class ClaudeService
{
    private $client;
    private $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.claude.key');
    }

    public function generateJuzoorQuiz($subject, $gradeLevel, $topic, $rootSettings, $includePassage = false, $passageTopic = null)
    {
        $prompt = $this->buildJuzoorPrompt($subject, $gradeLevel, $topic, $rootSettings, $includePassage, $passageTopic);

        $response = $this->client->post('https://api.anthropic.com/v1/messages', [
            'headers' => [
                'x-api-key' => $this->apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ],
            'json' => [
                'model' => 'claude-3-opus-20240229',
                'max_tokens' => 4000,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ]
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        if (isset($data['content'][0]['text'])) {
            return $this->parseQuizResponse($data['content'][0]['text']);
        }

        throw new \Exception('Invalid response from Claude API');
    }

    public function generateResultReport(Result $result, Quiz $quiz)
    {
        $prompt = $this->buildReportPrompt($result, $quiz);

        $response = $this->client->post('https://api.anthropic.com/v1/messages', [
            'headers' => [
                'x-api-key' => $this->apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ],
            'json' => [
                'model' => 'claude-3-opus-20240229',
                'max_tokens' => 2000,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ]
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        if (isset($data['content'][0]['text'])) {
            return $data['content'][0]['text'];
        }

        throw new \Exception('Invalid response from Claude API');
    }

    private function buildJuzoorPrompt($subject, $gradeLevel, $topic, $rootSettings, $includePassage, $passageTopic)
    {
        $subjectNames = [
            'arabic' => 'اللغة العربية',
            'english' => 'اللغة الإنجليزية',
            'hebrew' => 'اللغة العبرية'
        ];

        $prompt = "أنت خبير تعليمي متخصص في نموذج جُذور التعليمي. قم بإنشاء اختبار وفق المواصفات التالية:\n\n";
        $prompt .= "المادة: {$subjectNames[$subject]}\n";
        $prompt .= "الصف: $gradeLevel\n";
        $prompt .= "الموضوع: $topic\n\n";

        $prompt .= "نموذج جُذور التعليمي يحتوي على أربعة جذور:\n";
        $prompt .= "1. جَوهر (Jawhar): يركز على 'ما هو؟' - التعريفات والمفاهيم الأساسية\n";
        $prompt .= "2. ذِهن (Zihn): يركز على 'كيف يعمل؟' - الآليات والعمليات والأسباب\n";
        $prompt .= "3. وَصلات (Waslat): يركز على 'كيف يرتبط؟' - العلاقات والروابط مع مفاهيم أخرى\n";
        $prompt .= "4. رُؤية (Roaya): يركز على 'كيف نستخدمه؟' - التطبيق العملي والإبداع\n\n";

        $prompt .= "كل جذر له ثلاثة مستويات عمق:\n";
        $prompt .= "- المستوى 1 (سطحي): أسئلة بسيطة ومباشرة\n";
        $prompt .= "- المستوى 2 (متوسط): أسئلة تحليلية أعمق\n";
        $prompt .= "- المستوى 3 (عميق): أسئلة تتطلب تفكيراً نقدياً وإبداعياً\n\n";

        $prompt .= "المطلوب توليد الأسئلة التالية:\n";
        foreach ($rootSettings as $rootKey => $root) {
            foreach ($root['levels'] as $level) {
                if ($level['count'] > 0) {
                    $rootName = $this->getRootName($rootKey);
                    $prompt .= "- $rootName (مستوى {$level['depth']}): {$level['count']} أسئلة\n";
                }
            }
        }

        if ($includePassage) {
            $passageAbout = $passageTopic ?: $topic;
            $prompt .= "\nيجب أن يبدأ الاختبار بنص قراءة مناسب للصف $gradeLevel حول موضوع: $passageAbout\n";
            $prompt .= "النص يجب أن يكون بين 150-250 كلمة ومناسب لمستوى الطلاب.\n";
        }

        $prompt .= "\nالتعليمات:\n";
        $prompt .= "1. كل سؤال يجب أن يكون من نوع اختيار من متعدد مع 4 خيارات\n";
        $prompt .= "2. الأسئلة يجب أن تكون باللغة العربية حتى لو كانت المادة إنجليزية أو عبرية\n";
        $prompt .= "3. الأسئلة يجب أن تكون مناسبة لمستوى الصف المحدد\n";
        $prompt .= "4. تأكد من أن كل سؤال يتبع فلسفة الجذر المخصص له\n\n";

        $prompt .= "قم بإرجاع النتيجة بصيغة JSON بالشكل التالي:\n";
        $prompt .= "{\n";
        if ($includePassage) {
            $prompt .= '  "passage_title": "عنوان النص",\n';
            $prompt .= '  "passage": "نص القراءة الكامل",\n';
        }
        $prompt .= '  "questions": [\n';
        $prompt .= '    {\n';
        $prompt .= '      "question": "نص السؤال",\n';
        $prompt .= '      "root_type": "jawhar|zihn|waslat|roaya",\n';
        $prompt .= '      "depth_level": 1|2|3,\n';
        $prompt .= '      "options": ["خيار أ", "خيار ب", "خيار ج", "خيار د"],\n';
        $prompt .= '      "correct_answer": "الإجابة الصحيحة"\n';
        $prompt .= '    }\n';
        $prompt .= '  ]\n';
        $prompt .= '}';

        return $prompt;
    }

    private function buildReportPrompt(Result $result, Quiz $quiz)
    {
        $scores = $result->scores;
        $userName = $result->user ? $result->user->name : 'الطالب';

        $prompt = "قم بإنشاء تقرير تفصيلي لنتائج اختبار جُذور التعليمي.\n\n";

        $prompt .= "معلومات الاختبار:\n";
        $prompt .= "- عنوان الاختبار: {$quiz->title}\n";
        $prompt .= "- المادة: {$quiz->subject}\n";
        $prompt .= "- الصف: {$quiz->grade_level}\n";
        $prompt .= "- اسم الطالب: $userName\n";
        $prompt .= "- النتيجة الإجمالية: {$result->total_score}%\n\n";

        $prompt .= "نتائج الجذور:\n";
        $prompt .= "- جَوهر (الأساس): {$scores['jawhar']}%\n";
        $prompt .= "- ذِهن (التفكير): {$scores['zihn']}%\n";
        $prompt .= "- وَصلات (الروابط): {$scores['waslat']}%\n";
        $prompt .= "- رُؤية (التطبيق): {$scores['roaya']}%\n\n";

        $prompt .= "تفاصيل الإجابات:\n";
        foreach ($result->answers as $answer) {
            $question = $answer->question;
            $prompt .= "- {$question->question}\n";
            $prompt .= "  الجذر: {$this->getRootName($question->root_type)} (مستوى {$question->depth_level})\n";
            $prompt .= "  الإجابة: " . ($answer->is_correct ? "صحيحة ✓" : "خاطئة ✗") . "\n\n";
        }

        $prompt .= "المطلوب:\n";
        $prompt .= "1. تحليل شامل لأداء الطالب في كل جذر\n";
        $prompt .= "2. تحديد نقاط القوة والضعف\n";
        $prompt .= "3. توصيات محددة للتحسين\n";
        $prompt .= "4. خطة عمل مقترحة للتطوير\n";
        $prompt .= "5. كلمات تشجيعية ومحفزة\n\n";

        $prompt .= "اكتب التقرير بأسلوب تربوي إيجابي يركز على النمو والتطور وليس على النقص أو الفشل.";

        return $prompt;
    }

    private function getRootName($rootKey)
    {
        $names = [
            'jawhar' => 'جَوهر',
            'zihn' => 'ذِهن',
            'waslat' => 'وَصلات',
            'roaya' => 'رُؤية'
        ];

        return $names[$rootKey] ?? $rootKey;
    }

    private function parseQuizResponse($response)
    {
        // Try to extract JSON from the response
        $jsonMatch = [];
        preg_match('/\{.*\}/s', $response, $jsonMatch);

        if (!empty($jsonMatch)) {
            $jsonData = json_decode($jsonMatch[0], true);
            if ($jsonData && isset($jsonData['questions'])) {
                return $jsonData;
            }
        }

        // If JSON parsing fails, throw an error
        throw new \Exception('Could not parse quiz response from AI');
    }
}