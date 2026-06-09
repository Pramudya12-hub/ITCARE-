<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KnowledgeBaseArticle;

// Controller ini menangani tampilan artikel knowledge base untuk user biasa
class KnowledgeBaseController extends Controller
{
    // Menampilkan daftar artikel knowledge base yang sudah dipublish, dengan fitur pencarian dan filter kategori
    public function index(Request $request)
    {
        $query = KnowledgeBaseArticle::where('status', 'published');
        
        // Filter berdasarkan kata kunci judul jika ada
        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan kategori jika dipilih
        if ($request->category) {
            $query->where('category', $request->category);
        }

        $articles = $query->latest()->paginate(9)->withQueryString();
        return view('user.kb.index', compact('articles'));
    }

    // Menampilkan isi detail satu artikel knowledge base (hanya yang sudah dipublish)
    public function show(KnowledgeBaseArticle $article)
    {
        if ($article->status !== 'published') {
            abort(404);
        }
        return view('user.kb.show', compact('article'));
    }
}
