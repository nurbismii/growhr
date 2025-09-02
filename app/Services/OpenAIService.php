<?php

namespace App\Services;

use GuzzleHttp\Client;

class OpenAIService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.groq.com/openai/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . config('services.groq.key'),
                'Content-Type'  => 'application/json',
            ],
        ]);
    }

    public function translateToMandarin($text)
    {
        try {
            $response = $this->client->post('chat/completions', [
                'json' => [
                    'model' => 'openai/gpt-oss-20b',
                    'messages' => [
                        ['role' => 'system', 'content' => "You're a translator who only translates text into simple Chinese. Don't explain, just translate."],
                        ['role' => 'user', 'content' => $text],
                    ],
                    'max_tokens' => 200,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            return $data['choices'][0]['message']['content'] ?? 'Terjemahan gagal';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
