<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    public function generateText($prompt)
    {
        $apiKey = env('GROQ_API_KEY');

        if (!$apiKey) {
            return 'GROQ_API_KEY belum ditemukan di file .env.';
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'llama-3.1-8b-instant',

            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Kamu adalah AI Assistant untuk sistem IT Helpdesk.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],

            'temperature' => 0.7,
        ]);

        if (!$response->successful()) {
            return 'Groq API Error: ' . $response->body();
        }

        return $response->json('choices.0.message.content')
            ?? 'AI gagal memberikan respons.';
    }
}