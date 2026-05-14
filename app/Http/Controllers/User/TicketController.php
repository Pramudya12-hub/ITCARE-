<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Notification;
use app\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::where('user_id', Auth::id());

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

    public function create()
    {
        return view('user.tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'priority' => 'required|string',
            'description' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

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

    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }
        $ticket->load('comments.user', 'assignedSupport');
        return view('user.tickets.show', compact('ticket'));
    }

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
