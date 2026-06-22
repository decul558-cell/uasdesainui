<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller {
    public function index() {
        $monthlyRevenue = Order::where('status', 'paid')
            ->selectRaw('EXTRACT(MONTH FROM created_at) as month, EXTRACT(YEAR FROM created_at) as year, SUM(total_price - discount) as total')
            ->whereRaw('EXTRACT(YEAR FROM created_at) = ?', [now()->year])
            ->groupByRaw('EXTRACT(MONTH FROM created_at), EXTRACT(YEAR FROM created_at)')
            ->orderByRaw('EXTRACT(MONTH FROM created_at)')
            ->get();

        $topProducts = Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(10)->get();

        $totalRevenue   = Order::where('status', 'paid')->sum(DB::raw('total_price - discount'));
        $totalOrders    = Order::count();
        $totalUsers     = User::where('role', 'user')->count();
        $pendingOrders  = Order::where('status', 'pending')->count();

        return view('admin.reports.index', compact(
            'monthlyRevenue', 'topProducts',
            'totalRevenue', 'totalOrders',
            'totalUsers', 'pendingOrders'
        ));
    }

    public function exportCsv() {
        $orders = Order::with('user', 'items.product')->latest()->get();

        $filename = 'transaksi-' . now()->format('Y-m-d') . '.csv';
        $headers  = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=$filename"];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Kode Order', 'Pembeli', 'Total', 'Diskon', 'Final', 'Status', 'Tanggal']);
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_code,
                    $order->user->name,
                    $order->total_price,
                    $order->discount,
                    $order->total_price - $order->discount,
                    $order->status,
                    $order->created_at->format('d/m/Y'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}