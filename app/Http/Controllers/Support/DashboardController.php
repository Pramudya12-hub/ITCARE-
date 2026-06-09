<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use App\Models\KnowledgeBaseArticle;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

// Controller ini menangani halaman dashboard, profil, dan update profil untuk IT Support
class DashboardController extends Controller
{
    // Menampilkan dashboard IT Support beserta statistik tiket, grafik, dan daftar user aktif
    public function index()
    {
        // Hitung ringkasan statistik tiket dari semua user
        $stats = [
            'total' => Ticket::count(),
            'open' => Ticket::where('status', 'Open')->count(),
            'in_progress' => Ticket::where('status', 'In Progress')->count(),
            'closed' => Ticket::where('status', 'Closed')->count(),
            'kb_total' => KnowledgeBaseArticle::count(),
        ];

        // Hitung persentase tiket per kategori untuk ditampilkan di grafik
        $totalTickets = $stats['total'] ?: 1;
        $categories = Ticket::selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->get()
            ->map(function ($item) use ($totalTickets) {
                $item->percentage = round(($item->count / $totalTickets) * 100);
                return $item;
            });

        // Buat data volume tiket 7 hari terakhir untuk grafik mingguan
        $weeklyVolume = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $weeklyVolume[] = [
                'day' => $date->locale('id')->isoFormat('ddd'),
                'total' => Ticket::whereDate('created_at', $date->toDateString())->count(),
                'resolved' => Ticket::where('status', 'Closed')->whereDate('updated_at', $date->toDateString())->count(),
            ];
        }

        // Ambil 5 tiket terbaru dan 5 user paling aktif
        $latestTickets = Ticket::with('user')->latest()->take(5)->get();

        $activeUsers = 
        User::latest('last_active_at')
            ->take(5)
            ->get();

        return view('support.dashboard', compact('stats', 'latestTickets', 'categories', 'weeklyVolume', 'activeUsers'));
    }
    // Menampilkan halaman profil IT Support
    public function profile()
    {
        return view('support.profile');
    }

    // Menyimpan perubahan data profil IT Support (nama, email, password, dan foto profil)
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

        // Jika diisi password baru, enkripsi sebelum disimpan
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Jika ada foto baru yang diunggah, simpan ke storage
        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('profiles', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profile berhasil diperbarui.');
    }
}
