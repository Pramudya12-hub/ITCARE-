<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KnowledgeBaseArticle;
use Illuminate\Support\Facades\Auth;

// Controller ini menangani pengelolaan artikel knowledge base oleh IT Support (CRUD lengkap)
class KnowledgeBaseController extends Controller
{
    // Menampilkan semua artikel knowledge base (termasuk draft) untuk dikelola oleh IT Support
    public function index()
    {
        $articles = KnowledgeBaseArticle::with('creator')->latest()->paginate(15);
        return view('support.kb.index', compact('articles'));
    }

    // Menampilkan form untuk membuat artikel baru
    public function create()
    {
        return view('support.kb.create');
    }

    // Menyimpan artikel knowledge base baru yang dikirim melalui form
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        // Simpan thumbnail jika ada yang diunggah
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('kb', 'public');
        }

        KnowledgeBaseArticle::create([
            'title' => $request->title,
            'category' => $request->category,
            'content' => $request->content,
            'status' => $request->status,
            'thumbnail' => $thumbnailPath,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('support.kb.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    // Menampilkan form edit untuk artikel yang dipilih
    public function edit(KnowledgeBaseArticle $kb)
    {
        return view('support.kb.edit', compact('kb'));
    }

    // Menyimpan perubahan artikel knowledge base yang sudah diedit
    public function update(Request $request, KnowledgeBaseArticle $kb)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        // Ganti thumbnail jika ada yang diunggah
        if ($request->hasFile('thumbnail')) {
            $kb->thumbnail = $request->file('thumbnail')->store('kb', 'public');
        }

        $kb->update([
            'title' => $request->title,
            'category' => $request->category,
            'content' => $request->content,
            'status' => $request->status,
        ]);

        return redirect()->route('support.kb.index')->with('success', 'Artikel diperbarui.');
    }

    // Menghapus artikel knowledge base dari database
    public function destroy(KnowledgeBaseArticle $kb)
    {
        $kb->delete();
        return back()->with('success', 'Artikel dihapus.');
    }

    // Saat URL show diakses, langsung arahkan ke halaman edit
    public function show(KnowledgeBaseArticle $kb)
    {
        return redirect()->route('support.kb.edit', $kb);
    }
}
