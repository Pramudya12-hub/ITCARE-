<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeBaseArticle;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// Controller ini menangani halaman dashboard, profil, dan update profil untuk user biasa
class DashboardController extends Controller
{
    // Menampilkan halaman dashboard user beserta statistik tiket dan artikel terbaru
    public function index()
    {
        $user = User::findOrFail(Auth::id());

        // Hitung jumlah tiket milik user berdasarkan status
        $stats = [
            'total' => Ticket::where('user_id', $user->id)->count(),
            'open' => Ticket::where('user_id', $user->id)->where('status', 'Open')->count(),
            'in_progress' => Ticket::where('user_id', $user->id)->where('status', 'In Progress')->count(),
            'closed' => Ticket::where('user_id', $user->id)->where('status', 'Closed')->count(),
        ];

        // Ambil 3 tiket terbaru dan 3 artikel knowledge base terbaru untuk ditampilkan di dashboard
        $recentTickets = Ticket::where('user_id', $user->id)->latest()->take(3)->get();
        $popularKb = KnowledgeBaseArticle::where('status', 'published')->latest()->take(3)->get();

        return view('user.dashboard', compact('stats', 'recentTickets', 'popularKb'));
    }

    // Menampilkan halaman profil user
    public function profile()
    {
        return view('user.profile');
    }

    // Menyimpan perubahan data profil user (nama, email, password, dan foto profil)
    public function updateProfile(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Jika user mengisi password baru, enkripsi sebelum disimpan
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Jika user mengunggah foto baru, simpan ke storage
        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('profiles', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profile berhasil diperbarui.');
    }
}