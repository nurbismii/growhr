<?php

namespace App\Services;

use GuzzleHttp\Client;

class OpenAIService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.groq.com/openai/v1/', // pakai Groq API
            'headers' => [
                'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                'Content-Type'  => 'application/json',
            ],
        ]);

        // $this->client = new Client([
        //     'base_uri' => 'https://api.openai.com/v1/',
        //     'headers' => [
        //         'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
        //         'Content-Type'  => 'application/json',
        //     ],
        // ]);
    }

    public function translateToMandarin($text)
    {
        try {
            $response = $this->client->post('chat/completions', [
                'json' => [ 
                    'model' => 'llama-3.1-8b-instant',
                    // 'model' => 'gpt-3.5-turbo',
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
