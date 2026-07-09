@extends('layouts.app')
@section('title', 'Kelola Kupon')
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
    .admin-card-title{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:var(--brown);margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:2px solid var(--cream-dark);}
    .table{width:100%;border-collapse:collapse;}
    .table th{text-align:left;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--text-muted);padding:0.6rem 1rem;border-bottom:2px solid var(--cream-dark);white-space:nowrap;}
    .table td{padding:1rem;border-bottom:1px solid var(--cream-dark);font-size:0.875rem;vertical-align:middle;}
    .table tr:last-child td{border-bottom:none;}
    .table tr:hover td{background:var(--cream);}
    .coupon-code{font-family:monospace;font-size:0.95rem;font-weight:800;color:var(--brown);background:var(--cream);padding:0.25rem 0.6rem;border-radius:6px;letter-spacing:0.1em;}
    .badge-active{background:#ECFDF5;color:#065F46;display:inline-flex;padding:0.2rem 0.6rem;border-radius:50px;font-size:0.7rem;font-weight:700;}
    .badge-inactive{background:#F3F4F6;color:#6B7280;display:inline-flex;padding:0.2rem 0.6rem;border-radius:50px;font-size:0.7rem;font-weight:700;}
    .badge-expired{background:#FEF2F2;color:#991B1B;display:inline-flex;padding:0.2rem 0.6rem;border-radius:50px;font-size:0.7rem;font-weight:700;}
    .action-btns{display:flex;gap:0.5rem;}
    .btn-edit{display:inline-flex;align-items:center;gap:0.4rem;padding:0.4rem 0.9rem;background:rgba(59,130,246,0.1);color:#3B82F6;border-radius:8px;font-size:0.8rem;font-weight:700;text-decoration:none;transition:var(--transition);}
    .btn-edit:hover{background:#3B82F6;color:white;}
    .btn-del{display:inline-flex;align-items:center;gap:0.4rem;padding:0.4rem 0.9rem;background:rgba(239,68,68,0.1);color:#ef4444;border-radius:8px;font-size:0.8rem;font-weight:700;border:none;cursor:pointer;transition:var(--transition);font-family:'Plus Jakarta Sans',sans-serif;}
    .btn-del:hover{background:#ef4444;color:white;}
    .pagination-wrap{margin-top:1.5rem;display:flex;justify-content:center;}
    @media(max-width:768px){
        .admin-wrapper{grid-template-columns:1fr;}
        .admin-sidebar{display:none;}
        .table{display:block;overflow-x:auto;}
    }
</style>
@endpush

@section('content')
<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="admin-sidebar-brand">⚙️ Admin<span>Panel</span></div>
        <div class="admin-nav-label">Menu Utama</div>
        <a href="{{ route('admin.dashboard') }}" class="admin-nav-item"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <div class="admin-nav-label">Kelola</div>
        <a href="{{ route('admin.products.index') }}" class="admin-nav-item"><i class="fas fa-book"></i> Produk</a>
        <a href="{{ route('admin.categories.index') }}" class="admin-nav-item"><i class="fas fa-tags"></i> Kategori</a>
        <a href="{{ route('admin.articles.index') }}" class="admin-nav-item"><i class="fas fa-newspaper"></i> Artikel</a>
        <a href="{{ route('admin.users.index') }}" class="admin-nav-item"><i class="fas fa-users"></i> Pengguna</a>
        <a href="{{ route('admin.orders.index') }}" class="admin-nav-item"><i class="fas fa-shopping-bag"></i> Transaksi</a>
        <a href="{{ route('admin.coupons.index') }}" class="admin-nav-item active"><i class="fas fa-ticket-alt"></i> Kupon</a>
        <a href="{{ route('admin.reports.index') }}" class="admin-nav-item"><i class="fas fa-chart-bar"></i> Laporan</a>
        <a href="{{ route('admin.activity-logs.index') }}" class="admin-nav-item"><i class="fas fa-history"></i> Log Aktivitas</a>
        <div class="admin-nav-label">Lainnya</div>
        <a href="{{ route('home') }}" class="admin-nav-item"><i class="fas fa-store"></i> Lihat Toko</a>
        <form method="POST" action="{{ route('logout') }}" style="padding:0 0.75rem;margin-top:0.25rem;">
            @csrf
            <button type="submit" class="admin-nav-item" style="width:100%;background:none;border:none;cursor:pointer;color:rgba(255,255,255,0.7);font-family:'Plus Jakarta Sans',sans-serif;">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </form>
    </aside>

    <main class="admin-content">
        <div class="admin-page-title">Kelola Kupon</div>
        <div class="admin-page-sub">Buat dan kelola kode kupon diskon.</div>

        <div style="margin-bottom:1.5rem;">
            <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Buat Kupon Baru
            </a>
        </div>

        <div class="admin-card reveal">
            <div style="overflow-x:auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Tipe</th>
                            <th>Nilai</th>
                            <th>Min. Order</th>
                            <th>Penggunaan</th>
                            <th>Expired</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($coupons as $coupon)
                        <tr>
                            <td><span class="coupon-code">{{ $coupon->code }}</span></td>
                            <td style="color:var(--text-muted);">{{ $coupon->type === 'percent' ? 'Persen' : 'Nominal' }}</td>
                            <td style="font-weight:700;color:var(--orange);">
                                {{ $coupon->type === 'percent' ? $coupon->value . '%' : 'Rp ' . number_format($coupon->value, 0, ',', '.') }}
                            </td>
                            <td style="color:var(--text-muted);">Rp {{ number_format($coupon->min_order, 0, ',', '.') }}</td>
                            <td>
                                <span style="font-weight:700;color:var(--brown);">{{ $coupon->used_count }}</span>
                                <span style="color:var(--text-muted);">/ {{ $coupon->max_uses }}</span>
                            </td>
                            <td style="color:var(--text-muted);font-size:0.8rem;">
                                {{ $coupon->expired_at ? $coupon->expired_at->format('d M Y') : '—' }}
                            </td>
                            <td>
                                @if(!$coupon->is_active)
                                    <span class="badge-inactive">Nonaktif</span>
                                @elseif($coupon->expired_at && $coupon->expired_at->isPast())
                                    <span class="badge-expired">Kadaluarsa</span>
                                @elseif($coupon->used_count >= $coupon->max_uses)
                                    <span class="badge-expired">Habis</span>
                                @else
                                    <span class="badge-active">Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-del" onclick="return confirm('Hapus kupon ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrap">{{ $coupons->links() }}</div>
        </div>
    </main>
</div>
@endsection