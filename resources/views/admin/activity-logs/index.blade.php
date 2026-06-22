@extends('layouts.admin')
@section('title', 'Log Aktivitas')

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
    .table th{text-align:left;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--text-muted);padding:0.6rem 1rem;border-bottom:2px solid var(--cream-dark);}
    .table td{padding:1rem;border-bottom:1px solid var(--cream-dark);font-size:0.875rem;vertical-align:middle;}
    .table tr:last-child td{border-bottom:none;}
    .table tr:hover td{background:var(--cream);}
    @media(max-width:768px){
        .admin-wrapper{grid-template-columns:1fr;}
        .admin-sidebar{display:none;}
    }
</style>
@endpush

@section('content')
<div>
        <div class="admin-page-title">Log Aktivitas</div>
        <div class="admin-page-sub">Riwayat semua aktivitas di sistem.</div>
        <div class="admin-card">
            <div class="admin-card-title">Semua Aktivitas</div>
            @if($logs->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Aksi</th>
                        <th>Deskripsi</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $i => $log)
                    <tr>
                        <td style="color:var(--text-muted);">{{ $logs->firstItem() + $i }}</td>
                        <td style="font-size:0.8rem;color:var(--text-muted);">{{ $log->created_at->format('d M Y H:i') }}</td>
                        <td style="font-weight:600;color:var(--brown);">{{ $log->user?->name ?? 'System' }}</td>
                        <td>
                            <span style="background:rgba(232,98,42,0.1);color:var(--orange);padding:0.2rem 0.6rem;border-radius:50px;font-size:0.75rem;font-weight:700;">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td style="font-size:0.875rem;">{{ $log->description ?? '-' }}</td>
                        <td style="font-size:0.8rem;color:var(--text-muted);">{{ $log->ip_address ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:1.5rem;">{{ $logs->links() }}</div>
            @else
            <div style="text-align:center;padding:3rem;color:var(--text-muted);">
                <i class="fas fa-history" style="font-size:3rem;margin-bottom:1rem;opacity:0.3;display:block;"></i>
                <p>Belum ada aktivitas tercatat.</p>
            </div>
            @endif
        </div>
    @endsection
