<?php
namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller {
    public function index() {
        $wishlists = Wishlist::with('product.category')
                            ->where('user_id', Auth::id())
                            ->latest()->get();
        return view('pages.wishlist', compact('wishlists'));
    }

    public function toggle($productId) {
        $existing = Wishlist::where('user_id', Auth::id())
                           ->where('product_id', $productId)->first();
        if ($existing) {
            $existing->delete();
            return back()->with('success', 'Buku dihapus dari wishlist.');
        }
        Wishlist::create(['user_id' => Auth::id(), 'product_id' => $productId]);
        return back()->with('success', 'Buku ditambahkan ke wishlist!');
    }
}