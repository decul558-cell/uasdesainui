@extends('layouts.admin')
@section('title', 'Kelola Pengguna')
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
    .user-info{display:flex;align-items:center;gap:0.75rem;}
    .user-avatar{width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,var(--brown),var(--orange));display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:1rem;flex-shrink:0;}
    .user-name{font-weight:700;color:var(--brown);font-size:0.875rem;}
    .user-email{font-size:0.75rem;color:var(--text-muted);}
    .role-admin{background:rgba(232,98,42,0.1);color:var(--orange);display:inline-flex;padding:0.25rem 0.65rem;border-radius:50px;font-size:0.7rem;font-weight:700;}
    .role-user{background:rgba(59,130,246,0.1);color:#3B82F6;display:inline-flex;padding:0.25rem 0.65rem;border-radius:50px;font-size:0.7rem;font-weight:700;}
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
<div>
        <div class="admin-page-title">Kelola Pengguna</div>
        <div class="admin-page-sub">Lihat dan kelola semua akun pengguna.</div>

        <div class="admin-card reveal">
            <div class="admin-card-header">
                <div style="font-size:0.875rem;color:var(--text-muted);">
                    Total <strong style="color:var(--brown);">{{ $users->total() }}</strong> pengguna
                </div>
            </div>

            <div style="overflow-x:auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Pengguna</th>
                            <th>Role</th>
                            <th>Bergabung</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $i => $user)
                        <tr>
                            <td style="color:var(--text-muted);">{{ $users->firstItem() + $i }}</td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                    <div>
                                        <div class="user-name">{{ $user->name }}</div>
                                        <div class="user-email">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="role-admin">👑 Admin</span>
                                @else
                                    <span class="role-user">👤 User</span>
                                @endif
                            </td>
                            <td style="color:var(--text-muted);">{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-del" onclick="return confirm('Hapus pengguna ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                                @else
                                <span style="font-size:0.8rem;color:var(--text-muted);">— Akun Kamu</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrap">
                {{ $users->links() }}
            </div>
        </div>
    @endsection