<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')
                       ->where('user_id', Auth::id())
                       ->latest()->get();
        return view('pages.history', compact('orders'));
    }

    public function checkout()
    {
        $carts = Cart::with('product')->where('user_id', Auth::id())->get();
        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }
        $total = $carts->sum(fn($c) => $c->product->price * $c->quantity);
        return view('pages.checkout', compact('carts', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'payment_method'   => 'required|string',
        ]);

        $carts = Cart::with('product')->where('user_id', Auth::id())->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        // 1. CEK STOK SEBELUM ORDER
        foreach ($carts as $cart) {
            if (!$cart->product) continue;
            if ($cart->product->stock < $cart->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', "Stok buku '{$cart->product->title}' tidak mencukupi. Tersisa {$cart->product->stock}.");
            }
        }

        // 2. HITUNG TOTAL
        $total    = $carts->sum(fn($c) => $c->product->price * $c->quantity);
        $discount = 0;

        // 3. TERAPKAN KUPON (jika ada)
        if ($request->filled('coupon_code')) {
            $coupon = \App\Models\Coupon::where('code', $request->coupon_code)->first();
            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->calculateDiscount($total);
                $coupon->increment('used_count');
            }
        }

        // 4. BUAT ORDER
        $order = Order::create([
            'user_id'          => Auth::id(),
            'order_code'       => 'ORD-' . strtoupper(Str::random(8)),
            'total_price'      => $total,
            'discount'         => $discount,
            'coupon_code'      => $request->coupon_code ?? null,
            'status'           => 'pending',
            'payment_method'   => $request->payment_method,
            'shipping_address' => $request->shipping_address,
        ]);

        // 5. BUAT ORDER ITEMS + KURANGI STOK OTOMATIS
        foreach ($carts as $cart) {
            if (!$cart->product) continue;

            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $cart->product_id,
                'quantity'   => $cart->quantity,
                'price'      => $cart->product->price,
            ]);

            // Kurangi stok
            $cart->product->decrement('stock', $cart->quantity);
        }

        // 6. KIRIM NOTIFIKASI KE USER
        \App\Models\Notification::create([
            'user_id' => Auth::id(),
            'title'   => 'Pesanan Berhasil Dibuat!',
            'message' => 'Pesanan ' . $order->order_code . ' senilai Rp '
                . number_format($total - $discount, 0, ',', '.')
                . ' berhasil dibuat. Menunggu pembayaran.',
            'type'    => 'success',
            'is_read' => false,
        ]);

        // 7. KOSONGKAN KERANJANG
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Pesanan berhasil dibuat! Kode: ' . $order->order_code);
    }
}