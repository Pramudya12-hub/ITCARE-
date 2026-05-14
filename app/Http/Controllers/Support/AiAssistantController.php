<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\KnowledgeBaseArticle;
use App\Services\GeminiService;
use Illuminate\Http\Request;

class AiAssistantController extends Controller
{
    public function index()
    {
        $recurringIssues = Ticket::selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->first();

        $knowledgeGap = Ticket::whereNotIn('category', function ($query) {
            $query->select('category')
                ->from('knowledge_base_articles');
        })->count();

        $slaRisk = Ticket::whereIn('status', ['Open', 'In Progress'])
            ->where('created_at', '<=', now()->subHours(4))
            ->count();

        $aiResolutionRate = 64;

        $latestTickets = Ticket::latest()->take(5)->get();

        return view('support.ai.index', compact(
            'recurringIssues',
            'knowledgeGap',
            'slaRisk',
            'aiResolutionRate',
            'latestTickets'
        ));
    }

    public function summarizeLatest(GeminiService $gemini)
    {
        $tickets = Ticket::latest()->take(5)->get();

        if ($tickets->isEmpty()) {
            return back()->with('error', 'Belum ada tiket.');
        }

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

        $summary = $gemini->generateText($prompt);

        return back()->with('ai_summary', $summary);
    }
}