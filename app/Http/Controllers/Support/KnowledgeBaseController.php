<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KnowledgeBaseArticle;
use Illuminate\Support\Facades\Auth;

class KnowledgeBaseController extends Controller
{
    public function index()
    {
        $articles = KnowledgeBaseArticle::with('creator')->latest()->paginate(15);
        return view('support.kb.index', compact('articles'));
    }

    public function create()
    {
        return view('support.kb.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

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

    public function edit(KnowledgeBaseArticle $kb)
    {
        return view('support.kb.edit', compact('kb'));
    }

    public function update(Request $request, KnowledgeBaseArticle $kb)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

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

    public function destroy(KnowledgeBaseArticle $kb)
    {
        $kb->delete();
        return back()->with('success', 'Artikel dihapus.');
    }
    public function show(KnowledgeBaseArticle $kb)
    {
        return redirect()->route('support.kb.edit', $kb);
    }
}
