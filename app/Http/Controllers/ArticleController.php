<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('user')
                           ->where('status', 'published')
                           ->latest('published_at')
                           ->paginate(9);

        return view('pages.articles', compact('articles'));
    }

    public function show($slug)
    {
        $article = Article::with('user')
                          ->where('slug', $slug)
                          ->where('status', 'published')
                          ->firstOrFail();

        $related = Article::where('id', '!=', $article->id)
                          ->where('status', 'published')
                          ->latest()->take(3)->get();

        return view('pages.article-detail', compact('article', 'related'));
    }
}