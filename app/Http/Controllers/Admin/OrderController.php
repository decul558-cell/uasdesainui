<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,shipped,delivered,cancelled',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // 1. KEMBALIKAN STOK JIKA DIBATALKAN
        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            $order->load('items.product');
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            // Notifikasi ke user bahwa pesanan dibatalkan
            \App\Models\Notification::create([
                'user_id' => $order->user_id,
                'title'   => 'Pesanan Dibatalkan',
                'message' => 'Pesanan ' . $order->order_code . ' telah dibatalkan. Stok buku telah dikembalikan.',
                'type'    => 'warning',
                'is_read' => false,
            ]);
        }

        // 2. NOTIFIKASI UPDATE STATUS (selain cancelled)
        if ($newStatus !== $oldStatus && $newStatus !== 'cancelled') {
            $statusMessages = [
                'paid'      => 'Pesanan ' . $order->order_code . ' telah dikonfirmasi pembayarannya.',
                'shipped'   => 'Pesanan ' . $order->order_code . ' sedang dalam perjalanan ke alamatmu!',
                'delivered' => 'Pesanan ' . $order->order_code . ' telah diterima. Selamat membaca! 📚',
            ];

            if (isset($statusMessages[$newStatus])) {
                \App\Models\Notification::create([
                    'user_id' => $order->user_id,
                    'title'   => 'Update Pesanan',
                    'message' => $statusMessages[$newStatus],
                    'type'    => 'info',
                    'is_read' => false,
                ]);
            }
        }

        // 3. UPDATE STATUS ORDER
        $order->update([
            'status'  => $newStatus,
            'paid_at' => $newStatus === 'paid' ? now() : $order->paid_at,
        ]);

        return back()->with('success', 'Status pesanan berhasil diupdate!');
    }
}