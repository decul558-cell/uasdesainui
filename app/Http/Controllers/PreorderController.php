<?php
namespace App\Http\Controllers;

use App\Models\Preorder;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreorderController extends Controller {
    public function index() {
        $preorders = Preorder::with('product.category')
                            ->where('user_id', Auth::id())
                            ->latest()->get();
        return view('pages.preorders', compact('preorders'));
    }

    public function store(Request $request) {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1|max:10',
            'note'       => 'nullable|string|max:500',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock > 0) {
            return back()->with('error', 'Buku ini masih tersedia, langsung beli saja!');
        }

        $existing = Preorder::where('user_id', Auth::id())
                           ->where('product_id', $request->product_id)
                           ->first();

        if ($existing) {
            return back()->with('error', 'Kamu sudah melakukan pre-order untuk buku ini.');
        }

        Preorder::create([
            'user_id'    => Auth::id(),
            'product_id' => $request->product_id,
            'quantity'   => $request->quantity,
            'status'     => 'waiting',
            'note'       => $request->note,
        ]);

        // Kirim notifikasi
        \App\Models\Notification::create([
            'user_id' => Auth::id(),
            'title'   => 'Pre-Order Berhasil!',
            'message' => 'Pre-order buku "' . $product->title . '" berhasil didaftarkan. Kami akan memberitahu kamu saat stok tersedia.',
            'type'    => 'success',
            'is_read' => false,
        ]);

        return back()->with('success', 'Pre-order berhasil! Kami akan notifikasi kamu saat stok tersedia.');
    }

    public function destroy($id) {
        Preorder::where('id', $id)
                ->where('user_id', Auth::id())
                ->where('status', 'waiting')
                ->delete();
        return back()->with('success', 'Pre-order dibatalkan.');
    }
}