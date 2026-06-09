<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

// Service ini bertugas mengirim pertanyaan/prompt ke AI (Groq/LLaMA) dan mengembalikan jawabannya
class GeminiService
{
    // Fungsi utama untuk mengirim prompt ke AI dan mendapatkan respons teks
    public function generateText($prompt)
    {
        // Ambil API key dari file .env
        $apiKey = env('GROQ_API_KEY');

        // Jika API key tidak ada, langsung kembalikan pesan error
        if (!$apiKey) {
            return 'GROQ_API_KEY belum ditemukan di file .env.';
        }

        // Kirim prompt ke API Groq menggunakan model LLaMA
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

        // Jika AI tidak berhasil merespons, kembalikan pesan error dari API
        if (!$response->successful()) {
            return 'Groq API Error: ' . $response->body();
        }

        // Ambil isi jawaban AI, jika kosong tampilkan pesan default agar fitur tidak error
        return $response->json('choices.0.message.content')
            ?? 'AI gagal memberikan respons.';
    }
}