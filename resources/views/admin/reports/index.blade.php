@extends('layouts.admin')
@section('title', 'Laporan & Statistik')
@push('styles')
<style>
    .admin-wrapper{display:grid;grid-template-columns:260px 1fr;min-height:calc(100vh - 70px);}
    .admin-sidebar{background:var(--brown);color:white;padding:2rem 0;position:sticky;top:70px;height:calc(100vh - 70px);overflow-y:auto;}
    .admin-sidebar-brand{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;padding:0 1.5rem 1.5rem;border-bottom:1px solid rgba(255,255,255,0.1);margin-bottom:1rem;}
    .admin-sidebar-brand span{color:var(--orange);}
    .admin-nav-label{font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.4);padding:0.75rem 1.5rem 0.35rem;}
    .admin-nav-item{display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1.5rem;color:rgba(255,255,255,0.7);text-decoration:none;transition:var(--transition);font-size:0.9rem;font-weight:500;position:relative;}
    .admin-nav-item:hover{background:rgba(255,255,255,0.08);color:white;}
    .admin-nav-item.active{background:rgba(232,98,42,0.2);color:var(--orange);}
    .admin-nav-item.active::before{content:'';position:absolute;left:0;top:0;bottom:0;width:3px;background:var(--orange);border-radius:0 4px 4px 0;}
    .admin-nav-item i{width:18px;text-align:center;}
    .admin-content{padding:2rem;background:var(--cream);}
    .admin-page-title{font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:900;color:var(--brown);margin-bottom:0.35rem;}
    .admin-page-sub{color:var(--text-muted);font-size:0.9rem;margin-bottom:2rem;}
    .stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem;margin-bottom:2rem;}
    .stat-card{background:white;border-radius:16px;padding:1.5rem;box-shadow:var(--shadow);transition:var(--transition);}
    .stat-card:hover{transform:translateY(-3px);box-shadow:var(--shadow-lg);}
    .stat-icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin-bottom:1rem;}
    .stat-num{font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:900;color:var(--brown);line-height:1;}
    .stat-label{font-size:0.8rem;color:var(--text-muted);font-weight:500;margin-top:0.35rem;}
    .admin-card{background:white;border-radius:16px;padding:1.5rem;box-shadow:var(--shadow);margin-bottom:1.5rem;}
    .admin-card-title{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:var(--brown);margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:2px solid var(--cream-dark);display:flex;align-items:center;justify-content:space-between;}
    .chart-container{position:relative;height:300px;width:100%;}
    .chart-bars{display:flex;align-items:flex-end;gap:0.5rem;height:250px;padding:0 1rem;}
    .chart-bar-wrap{display:flex;flex-direction:column;align-items:center;gap:0.5rem;flex:1;}
    .chart-bar{width:100%;background:linear-gradient(180deg,var(--orange),var(--orange-light));border-radius:6px 6px 0 0;transition:all 0.5s ease;cursor:pointer;position:relative;}
    .chart-bar:hover{opacity:0.85;}
    .chart-bar-tooltip{position:absolute;top:-35px;left:50%;transform:translateX(-50%);background:var(--brown);color:white;padding:0.25rem 0.5rem;border-radius:6px;font-size:0.7rem;white-space:nowrap;opacity:0;transition:var(--transition);}
    .chart-bar:hover .chart-bar-tooltip{opacity:1;}
    .chart-label{font-size:0.7rem;color:var(--text-muted);font-weight:600;}
    .table{width:100%;border-collapse:collapse;}
    .table th{text-align:left;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--text-muted);padding:0.6rem 1rem;border-bottom:2px solid var(--cream-dark);}
    .table td{padding:0.85rem 1rem;border-bottom:1px solid var(--cream-dark);font-size:0.875rem;}
    .table tr:last-child td{border-bottom:none;}
    .table tr:hover td{background:var(--cream);}
    .rank-num{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.8rem;}
    .rank-1{background:#FFD700;color:#7D5A00;}
    .rank-2{background:#C0C0C0;color:#505050;}
    .rank-3{background:#CD7F32;color:#5C3500;}
    .rank-other{background:var(--cream-dark);color:var(--text-muted);}
    .reports-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;}
    @media(max-width:1024px){.stats-grid{grid-template-columns:repeat(2,1fr);}.reports-grid{grid-template-columns:1fr;}}
    @media(max-width:768px){.admin-wrapper{grid-template-columns:1fr;}.admin-sidebar{display:none;}.stats-grid{grid-template-columns:repeat(2,1fr);}}
</style>
@endpush

@section('content')
<div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
            <div>
                <div class="admin-page-title">Laporan & Statistik</div>
                <div class="admin-page-sub">Ringkasan performa toko tahun {{ now()->year }}</div>
            </div>
            <a href="{{ route('admin.reports.export') }}" class="btn btn-primary">
                <i class="fas fa-download"></i> Export CSV
            </a>
        </div>

        <!-- STATS -->
        <div class="stats-grid">
            <div class="stat-card reveal">
                <div class="stat-icon" style="background:rgba(232,98,42,0.1);color:var(--orange);"><i class="fas fa-money-bill-wave"></i></div>
                <div class="stat-num" style="font-size:1.3rem;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                <div class="stat-label">Total Pendapatan</div>
            </div>
            <div class="stat-card reveal" style="transition-delay:0.1s">
                <div class="stat-icon" style="background:rgba(59,130,246,0.1);color:#3B82F6;"><i class="fas fa-shopping-bag"></i></div>
                <div class="stat-num">{{ $totalOrders }}</div>
                <div class="stat-label">Total Pesanan</div>
            </div>
            <div class="stat-card reveal" style="transition-delay:0.2s">
                <div class="stat-icon" style="background:rgba(16,185,129,0.1);color:#10B981;"><i class="fas fa-users"></i></div>
                <div class="stat-num">{{ $totalUsers }}</div>
                <div class="stat-label">Total Pengguna</div>
            </div>
            <div class="stat-card reveal" style="transition-delay:0.3s">
                <div class="stat-icon" style="background:rgba(245,158,11,0.1);color:#F59E0B;"><i class="fas fa-clock"></i></div>
                <div class="stat-num">{{ $pendingOrders }}</div>
                <div class="stat-label">Pesanan Pending</div>
            </div>
        </div>

        <div class="reports-grid">
            <!-- CHART -->
            <div class="admin-card reveal">
                <div class="admin-card-title">
                    Pendapatan Per Bulan
                    <span style="font-size:0.8rem;color:var(--text-muted);font-family:'Plus Jakarta Sans',sans-serif;">{{ now()->year }}</span>
                </div>
                @php
                    $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
                    $revenueByMonth = collect(range(1,12))->map(function($m) use ($monthlyRevenue) {
                        $found = $monthlyRevenue->firstWhere('month', $m);
                        return $found ? $found->total : 0;
                    });
                    $maxRevenue = $revenueByMonth->max() ?: 1;
                @endphp
                <div class="chart-bars">
                    @foreach($months as $i => $month)
                    @php $height = ($revenueByMonth[$i] / $maxRevenue) * 220; $height = max($height, 4); @endphp
                    <div class="chart-bar-wrap">
                        <div class="chart-bar" style="height:{{ $height }}px;">
                            <div class="chart-bar-tooltip">Rp {{ number_format($revenueByMonth[$i], 0, ',', '.') }}</div>
                        </div>
                        <div class="chart-label">{{ $month }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- TOP PRODUCTS -->
            <div class="admin-card reveal">
                <div class="admin-card-title">Produk Terlaris</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Produk</th>
                            <th>Terjual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $i => $product)
                        <tr>
                            <td>
                                <div class="rank-num {{ $i === 0 ? 'rank-1' : ($i === 1 ? 'rank-2' : ($i === 2 ? 'rank-3' : 'rank-other')) }}">
                                    {{ $i + 1 }}
                                </div>
                            </td>
                            <td>
                                <div style="font-weight:700;color:var(--brown);font-size:0.85rem;">{{ Str::limit($product->title, 30) }}</div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">{{ $product->author }}</div>
                            </td>
                            <td>
                                <span style="font-weight:700;color:var(--orange);">{{ $product->order_items_count }}</span>
                                <span style="font-size:0.75rem;color:var(--text-muted);"> terjual</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" style="text-align:center;color:var(--text-muted);padding:2rem;">Belum ada data penjualan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endsection