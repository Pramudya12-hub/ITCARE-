<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\KnowledgeBaseArticle;

class SearchController extends Controller
{
    // Menampilkan halaman pencarian dan memproses kata kunci yang diketik user
    public function index(Request $request)
    {
        // Mengambil kata kunci pencarian dari input bernama "q"
        $query = $request->q;

        // Menyiapkan data kosong terlebih dahulu agar tidak error saat belum ada pencarian
        $tickets = collect();
        $articles = collect();

        // Jika user memasukkan kata kunci pencarian, maka sistem akan mulai mencari data
        if ($query) {

            // Mencari tiket berdasarkan judul, deskripsi, atau kategori yang sesuai dengan kata kunci
            $tickets = Ticket::where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->orWhere('category', 'like', "%{$query}%")
                ->latest()
                ->get();

            // Mencari artikel knowledge base yang sudah dipublish dan sesuai dengan kata kunci
            $articles = KnowledgeBaseArticle::where('status', 'published')
                ->where(function ($q) use ($query) {
                    // Pencarian artikel dilakukan berdasarkan judul, isi artikel, atau kategori
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('content', 'like', "%{$query}%")
                      ->orWhere('category', 'like', "%{$query}%");
                })
                ->latest()
                ->get();
        }

        // Mengirim hasil pencarian ke halaman search.index untuk ditampilkan
        return view('search.index', compact(
            'query',
            'tickets',
            'articles'
        ));
    }
}
