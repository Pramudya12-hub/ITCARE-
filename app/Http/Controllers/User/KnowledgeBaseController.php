<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KnowledgeBaseArticle;

class KnowledgeBaseController extends Controller
{
    public function index(Request $request)
    {
        $query = KnowledgeBaseArticle::where('status', 'published');
        
        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        $articles = $query->latest()->paginate(9)->withQueryString();
        return view('user.kb.index', compact('articles'));
    }

    public function show(KnowledgeBaseArticle $article)
    {
        if ($article->status !== 'published') {
            abort(404);
        }
        return view('user.kb.show', compact('article'));
    }
}
