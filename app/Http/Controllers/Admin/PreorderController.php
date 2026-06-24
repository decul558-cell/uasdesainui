<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Preorder;
use App\Models\Product;

class PreorderController extends Controller {
    public function index() {
        $preorders = Preorder::with('user','product')
                            ->latest()->paginate(15);
        return view('admin.preorders.index', compact('preorders'));
    }

    public function notify($productId) {
        $product   = Product::findOrFail($productId);
        $preorders = Preorder::with('user')
                            ->where('product_id', $productId)
                            ->where('status', 'waiting')
                            ->get();

        foreach ($preorders as $preorder) {
            Notification::create([
                'user_id' => $preorder->user_id,
                'title'   => 'Stok Tersedia!',
                'message' => 'Buku "' . $product->title . '" yang kamu pre-order sudah tersedia! Segera beli sebelum habis.',
                'type'    => 'success',
                'is_read' => false,
            ]);

            $preorder->update([
                'status'       => 'ready',
                'notified_at'  => now(),
            ]);
        }

        return back()->with('success', $preorders->count() . ' user berhasil dinotifikasi!');
    }

    public function cancel($id) {
        $preorder = Preorder::with('product')->findOrFail($id);
        $preorder->update(['status' => 'cancelled']);

        Notification::create([
            'user_id' => $preorder->user_id,
            'title'   => 'Pre-Order Dibatalkan',
            'message' => 'Pre-order buku "' . $preorder->product->title . '" telah dibatalkan oleh admin.',
            'type'    => 'warning',
            'is_read' => false,
        ]);

        return back()->with('success', 'Pre-order dibatalkan.');
    }
}