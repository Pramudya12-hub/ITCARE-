<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Controller ini menangani semua aksi pengelolaan tiket dari sisi IT Support
class TicketController extends Controller
{
    // Menampilkan semua tiket dari semua user dengan fitur pencarian dan filter status/kategori
    public function index(Request $request)
    {
        $query = Ticket::with(['user', 'assignedSupport']);
        // Filter berdasarkan kata kunci pencarian
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
        // Filter berdasarkan status tiket jika dipilih
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan kategori jika dipilih
        if ($request->category) {
            $query->where('category', $request->category);
        }

        $tickets = $query->latest()->paginate(15);

        return view('support.tickets.index', compact('tickets'));
    }

    // Menampilkan detail satu tiket beserta komentar, data user, dan daftar IT Support untuk penugasan
    public function show(Ticket $ticket)
    {
        $ticket->load(['comments.user', 'user', 'assignedSupport']);

        // Ambil semua IT Support untuk dropdown penugasan
        $supportUsers = User::where('role', 'it_support')
            ->orderBy('name')
            ->get();

        return view('support.tickets.show', compact('ticket', 'supportUsers'));
    }

    // Mengubah status tiket (Open / In Progress / Closed) dan mencatat waktu respons/penyelesaian
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:Open,In Progress,Closed'
        ]);

        $data = [
            'status' => $request->status
        ];

        // Catat waktu pertama kali tiket direspons saat statusnya diubah ke In Progress
        if ($request->status === 'In Progress') {
            if (!$ticket->first_response_at) {
                $data['first_response_at'] = now();
            }
        }

        // Catat waktu penyelesaian saat tiket ditutup (Closed)
        if ($request->status === 'Closed') {
            if (!$ticket->first_response_at) {
                $data['first_response_at'] = now();
            }

            if (!$ticket->resolved_at) {
                $data['resolved_at'] = now();
            }
        }

        $ticket->update($data);

        return back()->with('success', 'Status tiket berhasil diubah.');
    }

    // Menugaskan tiket ke salah satu IT Support, dan otomatis ubah status ke 'In Progress' jika sebelumnya 'Open'
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

    // Menyimpan komentar/balasan dari IT Support pada tiket, dan mencatat waktu respons pertama
    public function comment(Request $request, Ticket $ticket)
    {
        $request->validate([
            'comment' => 'required|string'
        ]);

        $ticket->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        // Jika ini adalah respons pertama, catat waktunya untuk keperluan SLA
        if (!$ticket->first_response_at) {
            $ticket->update([
                'first_response_at' => now()
            ]);
        }
        return back()->with('success', 'Komentar ditambahkan.');
    }

    // Mengirim data tiket ke AI untuk mendapatkan rekomendasi solusi, lalu menyimpan hasilnya ke tiket
    public function generateAiRecommendation(Ticket $ticket, \App\Services\GeminiService $aiService)
    {
        // Susun prompt yang dikirim ke AI berisi detail tiket
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

        // Kirim prompt ke AI dan simpan hasil rekomendasinya ke tiket
        $response = $aiService->generateText($prompt);

        $ticket->update([
            'ai_recommendation' => $response
        ]);

        return back()->with('success', 'Rekomendasi AI berhasil di-generate.');
    }

    // Mengekspor semua data tiket menjadi file Excel untuk laporan
    public function exportCsv()
    {
        $tickets = Ticket::with(['user', 'assignedSupport'])->latest()->get();

        $fileName = 'laporan-ticket-itcare.xls';

        // Render view export sebagai HTML lalu kirim sebagai file Excel
        $html = view('support.tickets.export', compact('tickets'))->render();

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
