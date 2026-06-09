<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\KnowledgeBaseArticle;
use App\Services\GeminiService;
use Illuminate\Http\Request;

// Controller ini menangani fitur AI Assistant untuk IT Support (analisis tiket dan ringkasan AI)
class AiAssistantController extends Controller
{
    // Menampilkan halaman AI Assistant dengan berbagai statistik analitik tiket
    public function index()
    {
        // Cari kategori tiket yang paling sering muncul
        $recurringIssues = Ticket::selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->first();

        // Hitung tiket yang kategorinya belum ada di knowledge base (celah pengetahuan)
        $knowledgeGap = Ticket::whereNotIn('category', function ($query) {
            $query->select('category')
                ->from('knowledge_base_articles');
        })->count();

        // Hitung tiket yang sudah lebih dari 4 jam belum selesai (berisiko melanggar SLA)
        $slaRisk = Ticket::whereIn('status', ['Open', 'In Progress'])
            ->where('created_at', '<=', now()->subHours(4))
            ->count();

        $aiResolutionRate = 64;

        // Ambil 5 tiket terbaru untuk ditampilkan
        $latestTickets = Ticket::latest()->take(5)->get();

        return view('support.ai.index', compact(
            'recurringIssues',
            'knowledgeGap',
            'slaRisk',
            'aiResolutionRate',
            'latestTickets'
        ));
    }

    // Mengirim data 5 tiket terbaru ke AI untuk dianalisis dan diringkas
    public function summarizeLatest(GeminiService $gemini)
    {
        $tickets = Ticket::latest()->take(5)->get();

        // Jika belum ada tiket sama sekali, kembalikan pesan error
        if ($tickets->isEmpty()) {
            return back()->with('error', 'Belum ada tiket.');
        }

        // Susun teks dari semua tiket untuk dikirim ke AI
        $ticketText = '';

        foreach ($tickets as $ticket) {
            $ticketText .= "
            Judul: {$ticket->title}
            Kategori: {$ticket->category}
            Prioritas: {$ticket->priority}
            Status: {$ticket->status}
            Deskripsi: {$ticket->description}

            ";
        }

        // Susun prompt lengkap yang meminta AI menganalisis semua tiket di atas
        $prompt = "
        Anda adalah AI Assistant untuk sistem IT Helpdesk.

        Analisis ticket berikut dan berikan:

        1. Ringkasan kondisi umum
        2. Masalah yang paling sering muncul
        3. Prioritas penanganan
        4. Rekomendasi tindakan IT Support

        Data ticket:
        {$ticketText}
        ";

        // Kirim prompt ke AI dan kembalikan hasilnya ke halaman melalui session
        $summary = $gemini->generateText($prompt);

        return back()->with('ai_summary', $summary);
    }
}