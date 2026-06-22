@extends('layouts.admin')
@section('title', 'Kelola Transaksi')
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
    .admin-card{background:white;border-radius:16px;padding:1.5rem;box-shadow:var(--shadow);}
    .admin-card-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;}
    .table{width:100%;border-collapse:collapse;}
    .table th{text-align:left;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--text-muted);padding:0.6rem 1rem;border-bottom:2px solid var(--cream-dark);white-space:nowrap;}
    .table td{padding:1rem;border-bottom:1px solid var(--cream-dark);font-size:0.875rem;vertical-align:middle;}
    .table tr:last-child td{border-bottom:none;}
    .table tr:hover td{background:var(--cream);}
    .status-badge{display:inline-flex;align-items:center;gap:0.3rem;padding:0.25rem 0.65rem;border-radius:50px;font-size:0.7rem;font-weight:700;text-transform:uppercase;}
    .status-pending{background:#FEF3C7;color:#92400E;}
    .status-paid{background:#ECFDF5;color:#065F46;}
    .status-shipped{background:#EFF6FF;color:#1E40AF;}
    .status-delivered{background:#F0FDF4;color:#166534;}
    .status-cancelled{background:#FEF2F2;color:#991B1B;}
    .status-select{padding:0.4rem 0.75rem;border:1.5px solid var(--cream-dark);border-radius:8px;font-family:'Plus Jakarta Sans',sans-serif;font-size:0.8rem;font-weight:600;color:var(--brown);background:white;cursor:pointer;transition:var(--transition);}
    .status-select:focus{outline:none;border-color:var(--orange);}
    .order-detail{font-size:0.75rem;color:var(--text-muted);}
    .pagination-wrap{margin-top:1.5rem;display:flex;justify-content:center;}
    .filter-tabs{display:flex;gap:0.5rem;flex-wrap:wrap;}
    .filter-tab{padding:0.4rem 1rem;border-radius:50px;font-size:0.8rem;font-weight:600;text-decoration:none;transition:var(--transition);border:1.5px solid var(--cream-dark);color:var(--text-muted);}
    .filter-tab:hover{border-color:var(--orange);color:var(--orange);}
    .filter-tab.active{background:var(--orange);color:white;border-color:var(--orange);}
    @media(max-width:768px){
        .admin-wrapper{grid-template-columns:1fr;}
        .admin-sidebar{display:none;}
        .table{display:block;overflow-x:auto;}
    }
</style>
@endpush

@section('content')
<div>
        <div class="admin-page-title">Kelola Transaksi</div>
        <div class="admin-page-sub">Pantau dan update status semua pesanan.</div>

        <div class="admin-card reveal">
            <div class="admin-card-header">
                <div class="filter-tabs">
                    <a href="{{ route('admin.orders.index') }}" class="filter-tab {{ !request('status') ? 'active' : '' }}">Semua</a>
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="filter-tab {{ request('status') === 'pending' ? 'active' : '' }}">⏳ Pending</a>
                    <a href="{{ route('admin.orders.index', ['status' => 'paid']) }}" class="filter-tab {{ request('status') === 'paid' ? 'active' : '' }}">✅ Dibayar</a>
                    <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="filter-tab {{ request('status') === 'shipped' ? 'active' : '' }}">🚚 Dikirim</a>
                    <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="filter-tab {{ request('status') === 'delivered' ? 'active' : '' }}">📦 Diterima</a>
                </div>
                <div style="font-size:0.875rem;color:var(--text-muted);">
                    Total <strong style="color:var(--brown);">{{ $orders->total() }}</strong> pesanan
                </div>
            </div>

            <div style="overflow-x:auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode Pesanan</th>
                            <th>Pembeli</th>
                            <th>Total</th>
                            <th>Pembayaran</th>
                            <th>Status</th>
                            <th>Update Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $i => $order)
                        @php
                            $sc = ['pending'=>'status-pending','paid'=>'status-paid','shipped'=>'status-shipped','delivered'=>'status-delivered','cancelled'=>'status-cancelled'];
                            $sl = ['pending'=>'⏳ Pending','paid'=>'✅ Dibayar','shipped'=>'🚚 Dikirim','delivered'=>'📦 Diterima','cancelled'=>'❌ Batal'];
                        @endphp
                        <tr>
                            <td style="color:var(--text-muted);">{{ $orders->firstItem() + $i }}</td>
                            <td>
                                <div style="font-weight:700;color:var(--brown);">{{ $order->order_code }}</div>
                                <div class="order-detail">{{ $order->created_at->format('d M Y, H:i') }}</div>
                            </td>
                            <td>
                                <div style="font-weight:600;color:var(--brown);">{{ $order->user->name }}</div>
                                <div class="order-detail">{{ $order->user->email }}</div>
                            </td>
                            <td style="font-weight:800;color:var(--orange);">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </td>
                            <td style="color:var(--text-muted);">{{ $order->payment_method ?? '-' }}</td>
                            <td>
                                <span class="status-badge {{ $sc[$order->status] ?? '' }}">
                                    {{ $sl[$order->status] ?? $order->status }}
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                                    @csrf @method('PATCH')
                                    <div style="display:flex;gap:0.5rem;align-items:center;">
                                        <select name="status" class="status-select">
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                            <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>✅ Dibayar</option>
                                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>🚚 Dikirim</option>
                                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>📦 Diterima</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>❌ Batal</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrap">
                {{ $orders->links() }}
            </div>
        </div>
    @endsection