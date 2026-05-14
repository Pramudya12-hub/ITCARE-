<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['user', 'assignedSupport']);
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('category', 'like', '%' . $request->search . '%')
                    ->orWhere('status', 'like', '%' . $request->search . '%')
                    ->orWhere('priority', 'like', '%' . $request->search . '%')
                    ->orWhereHas('user', function ($user) use ($request) {
                        $user->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        $tickets = $query->latest()->paginate(15);

        return view('support.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['comments.user', 'user', 'assignedSupport']);

        $supportUsers = User::where('role', 'it_support')
            ->orderBy('name')
            ->get();

        return view('support.tickets.show', compact('ticket', 'supportUsers'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:Open,In Progress,Closed'
        ]);

        $data = [
            'status' => $request->status
        ];

        if ($request->status === 'Closed') {
            if (!$ticket->resolved_at) {
                $data['resolved_at'] = now();
            }

            if (!$ticket->first_response_at) {
                $data['first_response_at'] = now();
            }
        }

        $ticket->update($data);

        return back()->with('success', 'Status tiket berhasil diubah.');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $ticket->update([
            'assigned_to' => $request->assigned_to,
            'status' => $request->assigned_to && $ticket->status === 'Open'
                ? 'In Progress'
                : $ticket->status,
        ]);

        return back()->with('success', 'Tiket berhasil ditugaskan.');
    }

    public function comment(Request $request, Ticket $ticket)
    {
        $request->validate([
            'comment' => 'required|string'
        ]);

        $ticket->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        if (!$ticket->first_response_at) {
            $ticket->update([
                'first_response_at' => now()
            ]);
        }
        return back()->with('success', 'Komentar ditambahkan.');
    }
    
    public function generateAiRecommendation(Ticket $ticket, \App\Services\GeminiService $aiService)
    {
        $prompt = "Tolong berikan rekomendasi untuk tiket IT Helpdesk berikut:\n"
            . "Judul: " . $ticket->title . "\n"
            . "Kategori: " . $ticket->category . "\n"
            . "Prioritas: " . $ticket->priority . "\n"
            . "Deskripsi: " . $ticket->description . "\n\n"
            . "Berikan balasan dengan format berikut:\n"
            . "- Ringkasan Masalah:\n"
            . "- Kemungkinan Penyebab:\n"
            . "- Langkah Troubleshooting:\n"
            . "- Rekomendasi Tindakan IT Support:";

        $response = $aiService->generateText($prompt);

        $ticket->update([
            'ai_recommendation' => $response
        ]);

        return back()->with('success', 'Rekomendasi AI berhasil di-generate.');
    }

    public function exportCsv()
    {
        $tickets = Ticket::with(['user', 'assignedSupport'])->latest()->get();

        $fileName = 'laporan-ticket-itcare.xls';

        $html = view('support.tickets.export', compact('tickets'))->render();

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
