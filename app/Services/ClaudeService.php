<?php
namespace App\Services;

use GuzzleHttp\Client;

class ClaudeService
{
    private $client;
    private $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.claude.key');
    }

    public function generateQuiz($subject, $gradeLevel, $rootSettings)
    {
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
                    ['role' => 'user', 'content' => $this->buildPrompt($subject, $gradeLevel, $rootSettings)]
                ]
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    private function buildPrompt($subject, $gradeLevel, $rootSettings)
    {
        $prompt = "أنشئ اختبار للصف $gradeLevel في مادة $subject وفق نموذج جُذور التعليمي.\n";
        $prompt .= "يمكنك إضافة نص قراءة إذا كان مناسباً للموضوع.\n";
        $prompt .= "الإعدادات: " . json_encode($rootSettings, JSON_UNESCAPED_UNICODE);
        return $prompt;
    }
}