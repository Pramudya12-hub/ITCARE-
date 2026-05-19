<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\KnowledgeBaseArticle;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->q;

        $tickets = collect();
        $articles = collect();

        if ($query) {

            $tickets = Ticket::where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->orWhere('category', 'like', "%{$query}%")
                ->latest()
                ->get();

            $articles = KnowledgeBaseArticle::where('status', 'published')
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('content', 'like', "%{$query}%")
                      ->orWhere('category', 'like', "%{$query}%");
                })
                ->latest()
                ->get();
        }

        return view('search.index', compact(
            'query',
            'tickets',
            'articles'
        ));
    }
}