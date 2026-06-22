<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbandonedCartController extends Controller
{
    /**
     * Halaman daftar abandoned cart di admin.
     */
    public function index(Request $request)
    {
        // Ambil semua user yang punya cart abandon, digroup per user
        $query = Cart::abandoned()
            ->with(['user', 'product', 'bundle'])
            ->select('user_id',
                DB::raw('COUNT(*) as item_count'),
                DB::raw('SUM(COALESCE(price, 0) * quantity) as estimated_value'),
                DB::raw('MAX(COALESCE(last_activity_at, updated_at)) as last_seen')
            )
            ->groupBy('user_id');

        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%");
            });
        }

        // Filter rentang waktu
        if ($request->filled('range')) {
            $hours = match($request->range) {
                '2h'  => 2,
                '6h'  => 6,
                '24h' => 24,
                '7d'  => 168,
                default => null,
            };
            if ($hours) {
                $query->where(function ($q) use ($hours) {
                    $q->where('last_activity_at', '<=', now()->subHours($hours))
                      ->orWhere(function ($q2) use ($hours) {
                          $q2->whereNull('last_activity_at')
                             ->where('updated_at', '<=', now()->subHours($hours));
                      });
                });
            }
        }

        $abandonedGroups = $query->orderByDesc('last_seen')->paginate(15)->withQueryString();

        // Ambil detail item per user_id untuk ditampilkan
        $userIds = $abandonedGroups->pluck('user_id')->toArray();
        $cartItems = Cart::abandoned()
            ->with(['user', 'product', 'bundle']) // FIX: hapus 'product.coverImage'
            ->whereIn('user_id', $userIds)
            ->get()
            ->groupBy('user_id');

        // Stats summary
        $stats = [
            'total_users'    => Cart::abandoned()->distinct('user_id')->count('user_id'),
            'total_items'    => Cart::abandoned()->count(),
            'estimated_loss' => Cart::abandoned()->sum(DB::raw('COALESCE(price, 0) * quantity')),
            'avg_items'      => Cart::abandoned()->count() > 0
                ? round(Cart::abandoned()->count() / max(Cart::abandoned()->distinct('user_id')->count('user_id'), 1), 1)
                : 0,
        ];

        return view('admin.abandoned-carts.index', compact(
            'abandonedGroups', 'cartItems', 'stats'
        ));
    }

    /**
     * Detail cart abandon milik satu user.
     */
    public function show(int $userId)
    {
        $items = Cart::abandoned()
            ->with(['product', 'bundle'])
            ->where('user_id', $userId)
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('admin.abandoned-carts.index')
                ->with('error', 'Data tidak ditemukan.');
        }

        $user = $items->first()->user ?? \App\Models\User::find($userId);

        return view('admin.abandoned-carts.show', compact('items', 'user'));
    }

    /**
     * Tandai semua cart user tertentu sebagai "sudah ditangani" (hapus dari daftar abandon).
     */
    public function dismiss(int $userId)
    {
        Cart::where('user_id', $userId)->delete();

        return back()->with('success', 'Cart user berhasil dibersihkan.');
    }
}