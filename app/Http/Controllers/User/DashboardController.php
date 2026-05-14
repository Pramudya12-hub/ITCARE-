<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeBaseArticle;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(Auth::id());

        $stats = [
            'total' => Ticket::where('user_id', $user->id)->count(),
            'open' => Ticket::where('user_id', $user->id)->where('status', 'Open')->count(),
            'in_progress' => Ticket::where('user_id', $user->id)->where('status', 'In Progress')->count(),
            'closed' => Ticket::where('user_id', $user->id)->where('status', 'Closed')->count(),
        ];

        $recentTickets = Ticket::where('user_id', $user->id)->latest()->take(3)->get();
        $popularKb = KnowledgeBaseArticle::where('status', 'published')->latest()->take(3)->get();

        return view('user.dashboard', compact('stats', 'recentTickets', 'popularKb'));
    }

    public function profile()
    {
        return view('user.profile');
    }

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

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('profiles', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profile berhasil diperbarui.');
    }
}