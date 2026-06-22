<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BundleController extends Controller
{
    public function index()
    {
        $bundles = Bundle::with('items.product')
                    ->where('is_active', true)
                    ->where('stock', '>', 0)
                    ->latest()->get();
        return view('pages.bundles', compact('bundles'));
    }

    public function show($slug)
    {
        $bundle = Bundle::with('items.product.category')
                    ->where('slug', $slug)
                    ->where('is_active', true)
                    ->firstOrFail();
        return view('pages.bundle-detail', compact('bundle'));
    }

    public function addToCart(Request $request, Bundle $bundle)
    {
        if ($bundle->stock <= 0) {
            return back()->with('error', 'Stok bundle habis!');
        }

        // Simpan bundle ke cart sebagai item khusus
        $existing = Cart::where('user_id', Auth::id())
                        ->where('bundle_id', $bundle->id)
                        ->first();

        if ($existing) {
            $existing->increment('quantity');
        } else {
            Cart::create([
                'user_id'    => Auth::id(),
                'bundle_id'  => $bundle->id,
                'product_id' => null,
                'quantity'   => 1,
                'price'      => $bundle->bundle_price,
            ]);
        }

        return back()->with('success', 'Bundle ditambahkan ke keranjang!');
    }
}