<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Product;
use App\Models\Bundle;

class HomeController extends Controller
{
    public function index()
    {
        $products   = Product::with('category')->latest()->take(8)->get();
        $articles   = Article::where('status', 'published')->latest()->take(3)->get();
        $categories = Category::withCount('products')->get();
        $bundles    = Bundle::where('is_active', true)
                        ->where('stock', '>', 0)
                        ->withCount('items')
                        ->latest()
                        ->take(4)
                        ->get();

        return view('pages.home', compact('products', 'articles', 'categories', 'bundles'));
    }
}