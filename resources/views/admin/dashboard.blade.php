@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <div class="page-title">Dashboard</div>
    <div class="page-sub">Selamat datang, {{ auth()->user()->name }}! Berikut ringkasan toko kamu.</div>
</div>

@php
    $statCards = [
        ['icon'=>'fa-users',           'color'=>'#E8622A', 'bg'=>'rgba(232,98,42,0.1)',   'num'=>$totalUsers,    'label'=>'Total Pengguna'],
        ['icon'=>'fa-book',            'color'=>'#0D9488', 'bg'=>'rgba(13,148,136,0.1)',  'num'=>$totalProducts, 'label'=>'Total Produk'],
        ['icon'=>'fa-shopping-bag',    'color'=>'#7C3AED', 'bg'=>'rgba(124,58,237,0.1)',  'num'=>$totalOrders,   'label'=>'Total Pesanan'],
        ['icon'=>'fa-money-bill-wave', 'color'=>'#DB2777', 'bg'=>'rgba(219,39,119,0.1)',  'num'=>'Rp '.number_format($totalRevenue,0,',','.'), 'label'=>'Total Pendapatan'],
    ];
@endphp
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem;margin-bottom:2rem;">
    @foreach($statCards as $i => $s)
    <div class="admin-card reveal" style="transition-delay:{{ $i*0.1 }}s;margin-bottom:0;">
        <div style="width:48px;height:48px;border-radius:12px;background:{{ $s['bg'] }};color:{{ $s['color'] }};display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin-bottom:1rem;">
            <i class="fas {{ $s['icon'] }}"></i>
        </div>
        <div style="font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:900;color:var(--brown);line-height:1;">{{ $s['num'] }}</div>
        <div style="font-size:0.8rem;color:var(--text-muted);margin-top:0.35rem;">{{ $s['label'] }}</div>
    </div>
    @endforeach
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:1.5rem;">
    <div class="admin-card reveal">
        <div class="admin-card-title">
            Pesanan Terbaru
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <table class="table">
            <thead>
                <tr><th>Kode</th><th>Pembeli</th><th>Total</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                @php
                    $sc=['pending'=>'status-pending','paid'=>'status-paid','shipped'=>'status-shipped','delivered'=>'status-delivered','cancelled'=>'status-cancelled'];
                    $sl=['pending'=>'Menunggu','paid'=>'Dibayar','shipped'=>'Dikirim','delivered'=>'Diterima','cancelled'=>'Batal'];
                @endphp
                <tr>
                    <td><strong>{{ $order->order_code }}</strong></td>
                    <td>{{ $order->user->name }}</td>
                    <td style="font-weight:700;color:var(--orange);">Rp {{ number_format($order->total_price,0,',','.') }}</td>
                    <td><span class="{{ $sc[$order->status] ?? '' }}">{{ $sl[$order->status] ?? $order->status }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="admin-card reveal">
        <div class="admin-card-title">Akses Cepat</div>
        <div style="display:flex;flex-direction:column;gap:0.75rem;">
            @foreach([
                ['route'=>'admin.products.create','icon'=>'fa-plus-circle','color'=>'#E8622A','bg'=>'rgba(232,98,42,0.1)','title'=>'Tambah Produk','sub'=>'Tambah buku baru ke katalog'],
                ['route'=>'admin.articles.create','icon'=>'fa-pen','color'=>'#3B82F6','bg'=>'rgba(59,130,246,0.1)','title'=>'Tulis Artikel','sub'=>'Buat artikel atau blog baru'],
                ['route'=>'admin.orders.index','icon'=>'fa-clipboard-list','color'=>'#10B981','bg'=>'rgba(16,185,129,0.1)','title'=>'Kelola Pesanan','sub'=>'Update status transaksi'],
                ['route'=>'admin.reports.index','icon'=>'fa-chart-bar','color'=>'#8B5CF6','bg'=>'rgba(139,92,246,0.1)','title'=>'Lihat Laporan','sub'=>'Statistik penjualan'],
            ] as $link)
            <a href="{{ route($link['route']) }}" style="display:flex;align-items:center;gap:1rem;padding:0.85rem;border-radius:10px;text-decoration:none;color:inherit;border:1.5px solid var(--cream-dark);transition:var(--transition);" onmouseover="this.style.borderColor='var(--orange)'" onmouseout="this.style.borderColor='var(--cream-dark)'">
                <div style="width:38px;height:38px;border-radius:9px;background:{{ $link['bg'] }};color:{{ $link['color'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fas {{ $link['icon'] }}"></i></div>
                <div>
                    <div style="font-weight:700;font-size:0.875rem;color:var(--brown);">{{ $link['title'] }}</div>
                    <div style="font-size:0.75rem;color:var(--text-muted);">{{ $link['sub'] }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
