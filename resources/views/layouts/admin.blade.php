<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — TokoBuku Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root{--cream:#F3EFF6;--cream-dark:#E5DCEB;--brown:#3D2645;--brown-mid:#5B4B8A;--orange:#8E3B5C;--orange-light:#A8527A;--gold:#D4A24E;--text:#3D2645;--text-muted:#8B7A95;--shadow:0 4px 24px rgba(61,38,69,0.08);--shadow-lg:0 8px 40px rgba(61,38,69,0.18);--radius:12px;--transition:all 0.3s cubic-bezier(0.4,0,0.2,1);--sidebar-bg:#2C1B33;--sidebar-bg-end:#3D2645;--sidebar-width:260px;}
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--cream);color:var(--text);min-height:100vh;}

        .admin-topnav{position:sticky;top:0;z-index:1000;background:linear-gradient(135deg,var(--sidebar-bg) 0%,var(--brown) 100%);border-bottom:1px solid rgba(255,255,255,0.08);padding:0 2rem;height:64px;display:flex;align-items:center;justify-content:space-between;box-shadow:var(--shadow);}
        .admin-topnav-brand{font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:900;color:white;text-decoration:none;display:flex;align-items:center;gap:0.5rem;}
        .admin-topnav-brand span{color:var(--gold);}
        .admin-topnav-right{display:flex;align-items:center;gap:1rem;}
        .admin-topnav-user{display:flex;align-items:center;gap:0.75rem;font-size:0.875rem;font-weight:600;color:white;}
        .admin-topnav-avatar{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--orange),var(--gold));display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:0.9rem;}
        .btn-topnav{display:inline-flex;align-items:center;gap:0.5rem;padding:0.5rem 1rem;border-radius:8px;font-size:0.8rem;font-weight:600;cursor:pointer;transition:var(--transition);border:none;text-decoration:none;font-family:'Plus Jakarta Sans',sans-serif;}
        .btn-view-store{background:rgba(255,255,255,0.1);color:white;}
        .btn-view-store:hover{background:rgba(255,255,255,0.18);}
        .btn-logout{background:rgba(239,68,68,0.15);color:#FCA5A5;}
        .btn-logout:hover{background:#ef4444;color:white;}

        .admin-layout{display:flex;min-height:calc(100vh - 64px);}
        .admin-sidebar{width:var(--sidebar-width);background:linear-gradient(180deg,var(--sidebar-bg) 0%,var(--brown) 55%,var(--brown-mid) 100%);flex-shrink:0;position:sticky;top:64px;height:calc(100vh - 64px);overflow-y:auto;}
        .admin-sidebar::-webkit-scrollbar{width:4px;}
        .admin-sidebar::-webkit-scrollbar-thumb{background:rgba(255,255,255,0.15);border-radius:4px;}
        .sidebar-section{padding:1rem 0;border-bottom:1px solid rgba(255,255,255,0.06);}
        .sidebar-section:last-child{border-bottom:none;}
        .sidebar-label{font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.12em;color:rgba(255,255,255,0.4);padding:0.5rem 1.5rem;margin-bottom:0.25rem;}
        .sidebar-item{display:flex;align-items:center;gap:0.75rem;padding:0.7rem 1.5rem;color:rgba(255,255,255,0.7);text-decoration:none;transition:var(--transition);font-size:0.875rem;font-weight:500;position:relative;border-left:3px solid transparent;}
        .sidebar-item:hover{background:rgba(255,255,255,0.07);color:white;border-left-color:rgba(255,255,255,0.25);}
        .sidebar-item.active{background:rgba(212,162,78,0.18);color:var(--gold);border-left-color:var(--gold);}
        .sidebar-item i{width:18px;text-align:center;font-size:0.9rem;}
        .sidebar-item .badge-count{margin-left:auto;background:var(--gold);color:var(--brown);font-size:0.65rem;font-weight:700;padding:0.1rem 0.45rem;border-radius:50px;}

        .admin-main{flex:1;padding:2rem;overflow-x:hidden;}
        .page-header{margin-bottom:2rem;}
        .page-title{font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:900;color:var(--brown);margin-bottom:0.25rem;}
        .page-sub{color:var(--text-muted);font-size:0.875rem;}
        .admin-card{background:white;border-radius:16px;padding:1.5rem;box-shadow:var(--shadow);margin-bottom:1.5rem;}
        .admin-card-title{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:var(--brown);margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:2px solid var(--cream-dark);display:flex;align-items:center;justify-content:space-between;}
        .btn{display:inline-flex;align-items:center;gap:0.5rem;padding:0.6rem 1.4rem;border-radius:8px;font-family:'Plus Jakarta Sans',sans-serif;font-weight:600;font-size:0.875rem;cursor:pointer;transition:var(--transition);border:none;text-decoration:none;}
        .btn-primary{background:var(--orange);color:white;}
        .btn-primary:hover{background:var(--orange-light);transform:translateY(-1px);}
        .btn-outline{background:transparent;color:var(--brown);border:1.5px solid var(--cream-dark);}
        .btn-outline:hover{background:var(--cream);}
        .btn-sm{padding:0.4rem 1rem;font-size:0.8rem;}
        .btn-edit{display:inline-flex;align-items:center;gap:0.4rem;padding:0.4rem 0.9rem;background:rgba(91,75,138,0.1);color:var(--brown-mid);border-radius:8px;font-size:0.8rem;font-weight:700;text-decoration:none;transition:var(--transition);border:none;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;}
        .btn-edit:hover{background:var(--brown-mid);color:white;}
        .btn-del{display:inline-flex;align-items:center;gap:0.4rem;padding:0.4rem 0.9rem;background:rgba(239,68,68,0.1);color:#ef4444;border-radius:8px;font-size:0.8rem;font-weight:700;border:none;cursor:pointer;transition:var(--transition);font-family:'Plus Jakarta Sans',sans-serif;}
        .btn-del:hover{background:#ef4444;color:white;}
        .table{width:100%;border-collapse:collapse;}
        .table th{text-align:left;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--text-muted);padding:0.6rem 1rem;border-bottom:2px solid var(--cream-dark);white-space:nowrap;}
        .table td{padding:0.9rem 1rem;border-bottom:1px solid var(--cream-dark);font-size:0.875rem;vertical-align:middle;}
        .table tr:last-child td{border-bottom:none;}
        .table tr:hover td{background:var(--cream);}
        .form-group{margin-bottom:1.25rem;}
        .form-label{display:block;font-weight:600;font-size:0.875rem;color:var(--brown);margin-bottom:0.5rem;}
        .form-control{width:100%;padding:0.75rem 1rem;border:1.5px solid var(--cream-dark);border-radius:var(--radius);font-family:'Plus Jakarta Sans',sans-serif;font-size:0.9rem;color:var(--text);background:white;transition:var(--transition);}
        .form-control:focus{outline:none;border-color:var(--orange);box-shadow:0 0 0 3px rgba(142,59,92,0.12);}
        select.form-control{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%238B7A95' d='M6 8L1 3h10z'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 1rem center;padding-right:2.5rem;}
        textarea.form-control{resize:vertical;min-height:100px;}
        .form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}
        .alert{padding:1rem 1.5rem;border-radius:var(--radius);margin-bottom:1.5rem;font-weight:500;display:flex;align-items:center;gap:0.75rem;}
        .alert-success{background:#ECFDF5;color:#065F46;border:1px solid #A7F3D0;}
        .alert-error{background:#FEF2F2;color:#991B1B;border:1px solid #FECACA;}
        .status-pending{background:#FEF3C7;color:#92400E;display:inline-flex;align-items:center;gap:0.3rem;padding:0.25rem 0.65rem;border-radius:50px;font-size:0.7rem;font-weight:700;}
        .status-paid{background:#ECFDF5;color:#065F46;display:inline-flex;align-items:center;gap:0.3rem;padding:0.25rem 0.65rem;border-radius:50px;font-size:0.7rem;font-weight:700;}
        .status-shipped{background:#EFF6FF;color:#1E40AF;display:inline-flex;align-items:center;gap:0.3rem;padding:0.25rem 0.65rem;border-radius:50px;font-size:0.7rem;font-weight:700;}
        .status-delivered{background:#F0FDF4;color:#166534;display:inline-flex;align-items:center;gap:0.3rem;padding:0.25rem 0.65rem;border-radius:50px;font-size:0.7rem;font-weight:700;}
        .status-cancelled{background:#FEF2F2;color:#991B1B;display:inline-flex;align-items:center;gap:0.3rem;padding:0.25rem 0.65rem;border-radius:50px;font-size:0.7rem;font-weight:700;}
        .reveal{opacity:0;transform:translateY(20px);transition:all 0.6s cubic-bezier(0.4,0,0.2,1);}
        .reveal.visible{opacity:1;transform:translateY(0);}
        .sidebar-toggle{display:none;background:none;border:none;cursor:pointer;color:white;font-size:1.3rem;padding:0.5rem;}
        @media(max-width:1024px){
            .admin-sidebar{position:fixed;top:64px;left:-260px;height:calc(100vh - 64px);z-index:999;transition:left 0.3s ease;}
            .admin-sidebar.open{left:0;}
            .sidebar-toggle{display:flex;}
        }
    </style>
    @stack('styles')
</head>
<body>
<nav class="admin-topnav">
    <div style="display:flex;align-items:center;gap:1rem;">
        <button class="sidebar-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
        <a href="{{ route('admin.dashboard') }}" class="admin-topnav-brand">📚 Pustaka<span>Nusantara</span>
            <span style="font-size:0.7rem;background:var(--gold);color:var(--brown);padding:0.15rem 0.5rem;border-radius:4px;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;margin-left:0.25rem;">ADMIN</span>
        </a>
    </div>
    <div class="admin-topnav-right">
        <a href="{{ route('home') }}" class="btn-topnav btn-view-store" target="_blank"><i class="fas fa-store"></i> Lihat Toko</a>
        <div class="admin-topnav-user">
            <div class="admin-topnav-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <span>{{ auth()->user()->name }}</span>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-topnav btn-logout"><i class="fas fa-sign-out-alt"></i> Keluar</button>
        </form>
    </div>
</nav>

<div class="admin-layout">
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-section">
            <div class="sidebar-label">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </div>
        <div class="sidebar-section">
            <div class="sidebar-label">Konten</div>
            <a href="{{ route('admin.products.index') }}" class="sidebar-item {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                <i class="fas fa-book"></i> Produk
            </a>
            <a href="{{ route('admin.categories.index') }}" class="sidebar-item {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i> Kategori
            </a>
            <a href="{{ route('admin.articles.index') }}" class="sidebar-item {{ request()->routeIs('admin.articles*') ? 'active' : '' }}">
                <i class="fas fa-newspaper"></i> Artikel
            </a>
        </div>
        <div class="sidebar-section">
            <div class="sidebar-label">Transaksi</div>
            <a href="{{ route('admin.orders.index') }}" class="sidebar-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                <i class="fas fa-shopping-bag"></i> Pesanan
                @php $pending = \App\Models\Order::where('status','pending')->count(); @endphp
                @if($pending > 0)<span class="badge-count">{{ $pending }}</span>@endif
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="sidebar-item {{ request()->routeIs('admin.coupons*') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt"></i> Kupon
            </a>
        </div>
        <div class="sidebar-section">
            <div class="sidebar-label">Pengguna</div>
            <a href="{{ route('admin.users.index') }}" class="sidebar-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Pengguna
            </a>
        </div>
        
        <!-- SECTION BARU: KOMUNIKASI -->
        <div class="sidebar-section">
            <div class="sidebar-label">Komunikasi</div>
            <a href="{{ route('admin.chats.index') }}" class="sidebar-item {{ request()->routeIs('admin.chats*') ? 'active' : '' }}">
                <i class="fas fa-comments"></i> Live Chat
                @php
                    $openChats = \App\Models\ChatConversation::where('status', 'open')->count();
                @endphp
                @if($openChats > 0)<span class="badge-count">{{ $openChats }}</span>@endif
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-label">Konten Lainnya</div>
            <a href="{{ route('admin.announcements.index') }}" class="sidebar-item {{ request()->routeIs('admin.announcements*') ? 'active' : '' }}">
                <i class="fas fa-bullhorn"></i> Pengumuman
            </a>
            <a href="{{ route('admin.banners.index') }}" class="sidebar-item {{ request()->routeIs('admin.banners*') ? 'active' : '' }}">
                <i class="fas fa-images"></i> Banner
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="sidebar-item {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
                <i class="fas fa-star"></i> Review
                @php $pendingReviews = \App\Models\Review::where('is_approved', false)->count(); @endphp
                @if($pendingReviews > 0)<span class="badge-count">{{ $pendingReviews }}</span>@endif
            </a>
            <a href="{{ route('admin.bundles.index') }}" class="sidebar-item {{ request()->routeIs('admin.bundles*') ? 'active' : '' }}">
                <i class="fas fa-box-open"></i> Bundle
            </a>
            <a href="{{ route('admin.abandoned-carts.index') }}" class="sidebar-item {{ request()->routeIs('admin.abandoned-carts*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i> Abandoned Cart
                @php $abandonCount = \App\Models\Cart::abandoned()->distinct('user_id')->count('user_id'); @endphp
                @if($abandonCount > 0)<span class="badge-count">{{ $abandonCount }}</span>@endif
            </a>
        </div>
        <div class="sidebar-section">
            <div class="sidebar-label">Analitik</div>
            <a href="{{ route('admin.reports.index') }}" class="sidebar-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i> Laporan
            </a>
            <a href="{{ route('admin.activity-logs.index') }}" class="sidebar-item {{ request()->routeIs('admin.activity-logs*') ? 'active' : '' }}">
                <i class="fas fa-history"></i> Log Aktivitas
            </a>
        </div>
    </aside>

    <main class="admin-main">
        @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif
        @yield('content')
    </main>
</div>

<script>
function toggleSidebar() {
    document.getElementById('adminSidebar').classList.toggle('open');
}
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(el => {
        el.style.transition='all 0.5s ease';el.style.opacity='0';
        setTimeout(() => el.remove(), 500);
    });
}, 4000);
const observer = new IntersectionObserver((entries) => {
    entries.forEach(el => { if(el.isIntersecting) el.target.classList.add('visible'); });
},{threshold:0.1});
document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>
@stack('scripts')
</body>
</html>