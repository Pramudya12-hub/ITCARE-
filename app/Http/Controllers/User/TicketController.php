<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Notification;
use app\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// Controller ini menangani semua aksi tiket dari sisi user (lihat, buat, dan komentar)
class TicketController extends Controller
{
    // Menampilkan daftar tiket keluhan milik user yang sedang login, dengan fitur pencarian
    public function index(Request $request)
    {
        $query = Ticket::where('user_id', Auth::id());

        // Filter tiket berdasarkan kata kunci pencarian jika ada
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('category', 'like', '%' . $request->search . '%')
                    ->orWhere('status', 'like', '%' . $request->search . '%')
                    ->orWhere('priority', 'like', '%' . $request->search . '%');
            });
        }

        $tickets = $query->latest()->paginate(10);

        return view('user.tickets.index', compact('tickets'));
    }

    // Menampilkan form pengajuan keluhan baru
    public function create()
    {
        return view('user.tickets.create');
    }

    // Menyimpan keluhan baru yang dikirim melalui form pengajuan
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'priority' => 'required|string',
            'description' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Simpan gambar pendukung jika ada yang diunggah
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tickets', 'public');
        }

        Ticket::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'category' => $request->category,
            'priority' => $request->priority,
            'description' => $request->description,
            'image' => $imagePath,
            'status' => 'Open',
        ]);

        // Kirim notifikasi ke semua IT Support bahwa ada tiket baru masuk
        $supportUsers = User::where('role', 'it_support')->get();

        foreach ($supportUsers as $support) {

            Notification::create([
                'user_id' => $support->id,
                'title' => 'Tiket Baru',
                'message' => 'Ada pengajuan baru: ' . $request->title,
            ]);
        }

        return redirect()->route('user.tickets.index')->with('success', 'Keluhan berhasil diajukan.');
    }

    // Menampilkan detail tiket beserta komentar-komentarnya (hanya bisa dilihat oleh pemilik tiket)
    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }
        $ticket->load('comments.user', 'assignedSupport');
        return view('user.tickets.show', compact('ticket'));
    }

    // Menyimpan komentar baru dari user pada tiket tertentu
    public function comment(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate(['comment' => 'required|string']);

        $ticket->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Komentar ditambahkan.');
    }
}
