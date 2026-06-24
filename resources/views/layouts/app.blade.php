<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pustaka Nusantara') — Toko Buku Online</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --plum:#3D2645;--plum-dark:#2C1B33;--indigo:#5B4B8A;--magenta:#8E3B5C;
            --gold:#D4A24E;--mist:#EDE6F0;--mist-dark:#DCCEE2;--text:#2C1B33;
            --text-muted:#7d6488;--shadow:0 4px 24px rgba(61,38,69,0.10);
            --shadow-lg:0 8px 40px rgba(61,38,69,0.22);--radius:12px;
            --transition:all 0.3s cubic-bezier(0.4,0,0.2,1);
        }
        *{margin:0;padding:0;box-sizing:border-box;}
        html,body{overflow-x:hidden;max-width:100vw;}
        body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--mist);color:var(--text);min-height:100vh;}

        /* NAVBAR */
        .navbar{position:sticky;top:0;z-index:1000;background:var(--plum-dark);padding:0 2rem;height:74px;display:flex;align-items:center;justify-content:space-between;box-shadow:0 4px 24px rgba(0,0,0,0.25);}
        .navbar-brand{display:flex;align-items:center;gap:0.6rem;font-family:'Playfair Display',serif;font-size:1.25rem;font-weight:900;color:var(--mist);text-decoration:none;}
        .navbar-brand span{color:var(--gold);}
        .navbar-pill{display:flex;gap:0.35rem;background:rgba(255,255,255,0.05);padding:0.3rem;border-radius:50px;}
        .navbar-pill a{text-decoration:none;color:#b39ec0;font-weight:700;font-size:0.8rem;padding:0.55rem 1.1rem;border-radius:50px;transition:var(--transition);white-space:nowrap;}
        .navbar-pill a:hover{color:var(--mist);background:rgba(255,255,255,0.06);}
        .navbar-pill a.active{background:var(--gold);color:var(--plum-dark);}
        .navbar-actions{display:flex;align-items:center;gap:0.75rem;}
        .btn{display:inline-flex;align-items:center;gap:0.5rem;padding:0.6rem 1.3rem;border-radius:50px;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:0.85rem;cursor:pointer;transition:var(--transition);border:none;text-decoration:none;}
        .btn-primary{background:var(--gold);color:var(--plum-dark);}
        .btn-primary:hover{background:#e0b264;transform:translateY(-1px);box-shadow:0 4px 16px rgba(212,162,78,0.4);}
        .btn-outline{background:transparent;color:var(--mist);border:1.5px solid rgba(237,230,240,0.25);}
        .btn-outline:hover{background:rgba(255,255,255,0.08);border-color:rgba(237,230,240,0.5);}
        .btn-sm{padding:0.4rem 1rem;font-size:0.8rem;}
        .btn-lg{padding:0.9rem 2rem;font-size:1rem;}
        .icon-btn{position:relative;width:38px;height:38px;border-radius:10px;background:rgba(212,162,78,0.12);border:1px solid rgba(212,162,78,0.25);display:flex;align-items:center;justify-content:center;color:var(--gold);text-decoration:none;transition:var(--transition);flex-shrink:0;}
        .icon-btn:hover{background:rgba(212,162,78,0.22);}
        .icon-btn .badge{position:absolute;top:-6px;right:-6px;background:var(--magenta);color:var(--mist);font-size:0.62rem;font-weight:700;width:18px;height:18px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid var(--plum-dark);}
        .dropdown{position:relative;}
        .profile-pill{display:flex;align-items:center;gap:0.5rem;background:rgba(255,255,255,0.06);padding:0.35rem 0.8rem 0.35rem 0.35rem;border-radius:50px;border:1px solid rgba(237,230,240,0.12);cursor:pointer;transition:var(--transition);}
        .profile-pill:hover{background:rgba(255,255,255,0.1);}
        .profile-avatar{width:26px;height:26px;min-width:26px;max-width:26px;min-height:26px;max-height:26px;border-radius:50%;background:var(--magenta);display:flex;align-items:center;justify-content:center;font-size:0.7rem;color:var(--mist);font-weight:700;flex-shrink:0;line-height:1;}
        .profile-pill span{color:var(--mist);font-size:0.8rem;font-weight:700;}
        .profile-pill i{color:#b39ec0;font-size:0.65rem;}
        .dropdown-menu{position:absolute;top:calc(100% + 12px);right:0;background:var(--plum);border-radius:var(--radius);box-shadow:var(--shadow-lg);min-width:220px;padding:0;opacity:0;visibility:hidden;transform:translateY(-8px);transition:var(--transition);border:1px solid rgba(255,255,255,0.08);overflow:hidden;}
        .dropdown:hover .dropdown-menu{opacity:1;visibility:visible;transform:translateY(0);}
        .dropdown-menu a,.dropdown-menu button{display:flex;align-items:center;gap:0.75rem;padding:0.65rem 1.1rem;color:#d9c9e0;text-decoration:none;font-size:0.85rem;font-weight:500;transition:var(--transition);background:none;border:none;width:100%;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;text-align:left;}
        .dropdown-menu a i{color:var(--gold);width:16px;}
        .dropdown-menu a:hover,.dropdown-menu button:hover{background:rgba(255,255,255,0.06);color:var(--mist);}
        .dropdown-menu hr{border:none;border-top:1px solid rgba(255,255,255,0.08);margin:0;}
        .dropdown-menu .logout-btn{color:#e08a8a;}
        .dropdown-menu .logout-btn i{color:#e08a8a;}
        .dropdown-menu .logout-btn:hover{background:rgba(224,138,138,0.1);color:#f0a8a8;}
        .dropdown-user-info{padding:0.9rem 1.1rem;border-bottom:1px solid rgba(255,255,255,0.08);}
        .dropdown-user-info .name{font-weight:700;font-size:0.85rem;color:var(--mist);}
        .dropdown-user-info .email{font-size:0.72rem;color:#9d84a8;margin-top:0.1rem;}
        .notif-badge{margin-left:auto;background:var(--magenta);color:var(--mist);font-size:0.62rem;font-weight:700;padding:1px 6px;border-radius:50px;}

        /* ALERTS */
        .alert{padding:1rem 1.5rem;border-radius:var(--radius);margin-bottom:1.5rem;font-weight:500;display:flex;align-items:center;gap:0.75rem;animation:slideDown 0.3s ease;}
        @keyframes slideDown{from{opacity:0;transform:translateY(-10px);}to{opacity:1;transform:translateY(0);}}
        .alert-success{background:#E5F3EA;color:#1d5c33;border:1px solid #b8e0c4;}
        .alert-error{background:#FBE5E5;color:#8a2c2c;border:1px solid #f0bcbc;}

        /* FOOTER */
        footer{background:var(--plum-dark);color:rgba(237,230,240,0.75);padding:4rem 2rem 2rem;margin-top:6rem;}
        .footer-grid{max-width:1200px;margin:0 auto;display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:3rem;padding-bottom:3rem;border-bottom:1px solid rgba(255,255,255,0.08);}
        .footer-brand{display:flex;align-items:center;gap:0.5rem;font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:900;color:var(--mist);margin-bottom:1rem;}
        .footer-brand span{color:var(--gold);}
        .footer-desc{font-size:0.875rem;line-height:1.7;color:#9d84a8;}
        .footer-title{font-weight:700;color:var(--mist);margin-bottom:1.25rem;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.08em;}
        .footer-links{list-style:none;}
        .footer-links li{margin-bottom:0.6rem;}
        .footer-links a{color:#9d84a8;text-decoration:none;font-size:0.875rem;transition:var(--transition);}
        .footer-links a:hover{color:var(--gold);}
        .footer-bottom{max-width:1200px;margin:2rem auto 0;display:flex;justify-content:space-between;align-items:center;font-size:0.8rem;color:#6b5876;}
        .hamburger{display:none;background:none;border:none;cursor:pointer;padding:0.5rem;color:var(--mist);font-size:1.4rem;}

        /* SHARED */
        .container{max-width:1200px;margin:0 auto;padding:0 2rem;}
        .section{padding:5rem 0;}
        .section-title{font-family:'Playfair Display',serif;font-size:clamp(1.8rem,4vw,2.8rem);font-weight:900;color:var(--plum);line-height:1.2;}
        .section-header{display:flex;align-items:flex-end;justify-content:space-between;gap:1rem;flex-wrap:wrap;}
        .card{background:white;border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow);transition:var(--transition);border:1px solid rgba(61,38,69,0.06);}
        .card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg);}
        .reveal{opacity:0;transform:translateY(30px);transition:all 0.7s cubic-bezier(0.4,0,0.2,1);}
        .reveal.visible{opacity:1;transform:translateY(0);}
        .form-group{margin-bottom:1.25rem;}
        .form-label{display:block;font-weight:600;font-size:0.875rem;color:var(--plum);margin-bottom:0.5rem;}
        .form-control{width:100%;padding:0.75rem 1rem;border:1.5px solid var(--mist-dark);border-radius:var(--radius);font-family:'Plus Jakarta Sans',sans-serif;font-size:0.9rem;color:var(--text);background:white;transition:var(--transition);}
        .form-control:focus{outline:none;border-color:var(--gold);box-shadow:0 0 0 3px rgba(212,162,78,0.18);}
        .form-error{color:#c0392b;font-size:0.8rem;margin-top:0.35rem;}

        /* BOTTOM NAV — muncul di semua halaman mobile */
        .bottom-nav{display:none;position:fixed;bottom:0;left:0;right:0;background:var(--plum-dark);border-top:1px solid rgba(255,255,255,0.08);padding:0.6rem 0 calc(0.6rem + env(safe-area-inset-bottom));z-index:1000;box-shadow:0 -4px 20px rgba(0,0,0,0.3);}
        .bottom-nav-inner{display:flex;justify-content:space-around;align-items:center;max-width:500px;margin:0 auto;}
        .bottom-nav-item{display:flex;flex-direction:column;align-items:center;gap:0.2rem;text-decoration:none;color:#9d84a8;font-size:0.62rem;font-weight:600;padding:0.4rem 0.7rem;border-radius:10px;transition:var(--transition);position:relative;}
        .bottom-nav-item i{font-size:1.25rem;line-height:1;}
        .bottom-nav-item.active{color:var(--gold);background:rgba(212,162,78,0.15);border-radius:10px;}
        .bottom-nav-item .bnav-badge{position:absolute;top:0;right:2px;background:var(--magenta);color:white;font-size:0.55rem;font-weight:700;width:15px;height:15px;border-radius:50%;display:flex;align-items:center;justify-content:center;}

        @keyframes slideInLeft{from{transform:translateX(-100%);opacity:0;}to{transform:translateX(0);opacity:1;}} .nav-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;backdrop-filter:blur(2px);} .nav-overlay.show{display:block;} /* CHAT WIDGET */
        #chat-widget{position:fixed;bottom:5rem;right:1rem;z-index:9999;font-family:'Plus Jakarta Sans',sans-serif;}
        #chat-toggle{width:50px;height:50px;border-radius:50%;background:var(--plum);border:none;cursor:pointer;color:white;font-size:1.2rem;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 20px rgba(61,38,69,0.4);transition:all 0.3s ease;position:relative;margin-left:auto;}
        #chat-toggle:hover{transform:scale(1.08);background:var(--indigo);}
        .chat-notif-dot{position:absolute;top:-4px;right:-4px;background:#e74c3c;color:white;font-size:0.6rem;font-weight:700;width:18px;height:18px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid white;}
        #chat-panel{position:absolute;bottom:60px;right:0;width:300px;background:white;border-radius:16px;box-shadow:0 8px 40px rgba(61,38,69,0.22);display:none;flex-direction:column;overflow:hidden;border:1px solid rgba(61,38,69,0.1);}
        #chat-panel.open{display:flex;}
        #chat-header{background:var(--plum);padding:0.85rem 1rem;display:flex;align-items:center;justify-content:space-between;}
        #chat-messages{flex:1;max-height:280px;overflow-y:auto;padding:1rem;display:flex;flex-direction:column;gap:0.75rem;background:#f9f7fb;}
        #chat-messages::-webkit-scrollbar{width:3px;}
        #chat-messages::-webkit-scrollbar-thumb{background:rgba(61,38,69,0.2);border-radius:3px;}
        .chat-bubble-row{display:flex;align-items:flex-end;gap:0.5rem;}
        .chat-bubble-row.mine{flex-direction:row-reverse;}
        .chat-avatar{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;flex-shrink:0;}
        .chat-avatar.bot{background:#EDE6F0;color:#3D2645;}
        .chat-avatar.admin{background:#5B4B8A;color:white;}
        .chat-avatar.user{background:var(--magenta);color:white;}
        .chat-bubble{padding:0.55rem 0.85rem;border-radius:12px;font-size:0.82rem;line-height:1.5;max-width:200px;word-break:break-word;}
        .bot-bubble{background:white;color:#3D2645;border:1px solid #EDE6F0;border-bottom-left-radius:4px;}
        .user-bubble{background:var(--plum);color:white;border-bottom-right-radius:4px;}
        .chat-bubble-meta{font-size:0.65rem;color:#9d84a8;margin-top:0.2rem;}
        .chat-bubble-row.mine .chat-bubble-meta{text-align:right;}
        #chat-input-area{display:flex;align-items:center;gap:0.5rem;padding:0.75rem 1rem;border-top:1px solid #EDE6F0;background:white;}
        #chat-input{flex:1;border:1.5px solid #EDE6F0;border-radius:50px;padding:0.5rem 1rem;font-size:0.82rem;font-family:'Plus Jakarta Sans',sans-serif;outline:none;transition:border-color 0.2s;color:#3D2645;}
        #chat-input:focus{border-color:var(--plum);}
        #chat-send-btn{width:34px;height:34px;border-radius:50%;background:var(--plum);border:none;color:white;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:0.85rem;flex-shrink:0;}
        #chat-send-btn:hover{background:var(--indigo);}
        .chat-typing{display:flex;gap:4px;padding:0.5rem 0.75rem;background:white;border-radius:12px;border:1px solid #EDE6F0;width:fit-content;}
        .chat-typing span{width:6px;height:6px;border-radius:50%;background:#9d84a8;animation:typingDot 1.2s infinite;}
        .chat-typing span:nth-child(2){animation-delay:0.2s;}
        .chat-typing span:nth-child(3){animation-delay:0.4s;}
        @keyframes typingDot{0%,60%,100%{transform:translateY(0);}30%{transform:translateY(-4px);}}

        @media(max-width:900px){.navbar-pill{display:none;}}
        @media(max-width:768px){
            .hamburger{display:block;}
            .navbar-pill.mobile-open{display:flex;flex-direction:column;position:fixed;top:0;left:0;bottom:0;width:75%;max-width:280px;background:linear-gradient(180deg,var(--plum-dark) 0%,var(--plum) 100%);padding:0;gap:0;border-radius:0 16px 16px 0;box-shadow:4px 0 24px rgba(0,0,0,0.4);z-index:1001;animation:slideInLeft 0.25s ease;}
            .navbar-pill.mobile-open a{text-align:left;padding:0.75rem 1.5rem;border-left:3px solid transparent;border-radius:0;display:flex;align-items:center;gap:0.75rem;font-size:0.875rem;} .navbar-pill.mobile-open a.active{border-left-color:var(--gold);color:var(--gold);background:rgba(212,162,78,0.12);} .navbar-pill.mobile-open a:hover{border-left-color:rgba(255,255,255,0.2);background:rgba(255,255,255,0.06);border-radius:0;}
            .footer-grid{grid-template-columns:1fr;gap:1.25rem;} footer{margin-top:3rem;padding:2.5rem 1.5rem 1.5rem;}
            #chat-panel{width:calc(100vw - 4rem);}
            .bottom-nav{display:block;}
            body{padding-bottom:72px;}
            .container{padding:0 1rem;}
            /* Sembunyikan tombol Masuk/Daftar di navbar mobile — sudah ada di bottom nav */
            .navbar-actions .btn-outline,
            .navbar-actions .btn-primary{display:none;}
            /* Sembunyikan icon cart & profile pill di mobile — sudah ada di bottom nav */
            .navbar-actions .icon-btn,
            .navbar-actions .dropdown{display:none;}
        }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar">
    <a href="{{ route('home') }}" class="navbar-brand">
        <svg width="26" height="26" viewBox="0 0 30 30" style="flex-shrink:0;">
            <path d="M15 2 L26 22 L20 22 L20 27 L10 27 L10 22 L4 22 Z" fill="#D4A24E"/>
            <path d="M15 6 L15 22" stroke="#2C1B33" stroke-width="1.5"/>
            <path d="M9 22 Q15 17 21 22" stroke="#2C1B33" stroke-width="1.5" fill="none"/>
        </svg>
        Pustaka <span>Nusantara</span>
    </a>

    <ul class="navbar-pill" id="navMenu" style="list-style:none;"><li class="menu-header" style="display:none;padding:1.25rem 1.5rem;border-bottom:1px solid rgba(255,255,255,0.08);display:flex;align-items:center;justify-content:space-between;"><span style="display:flex;align-items:center;gap:0.5rem;font-family:Playfair Display,serif;font-weight:900;color:white;font-size:1rem;"><svg width="22" height="22" viewBox="0 0 30 30"><path d="M15 2 L26 22 L20 22 L20 27 L10 27 L10 22 L4 22 Z" fill="#D4A24E"/><path d="M15 6 L15 22" stroke="#2C1B33" stroke-width="1.5"/><path d="M9 22 Q15 17 21 22" stroke="#2C1B33" stroke-width="1.5" fill="none"/></svg>Pustaka <span style="color:var(--gold)">Nusantara</span></span><button onclick="closeMenu()" style="background:none;border:none;color:rgba(255,255,255,0.6);font-size:1.3rem;cursor:pointer;padding:0;"><i class="fas fa-times"></i></button></li>
        <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
        <li><a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">Katalog</a></li>
        <li><a href="{{ route('articles.index') }}" class="{{ request()->routeIs('articles.*') ? 'active' : '' }}">Artikel</a></li>
        @auth
        <li><a href="{{ route('reading-list.index') }}" class="{{ request()->routeIs('reading-list.*') ? 'active' : '' }}">Riwayat Baca</a></li>
        <li><a href="{{ route('preorders.index') }}" class="{{ request()->routeIs('preorders.*') ? 'active' : '' }}">Pre-Order</a></li>
        <li><a href="{{ route('wishlist.index') }}" class="{{ request()->routeIs('wishlist.*') ? 'active' : '' }}">Wishlist</a></li>
        @endauth
    </ul>

    <div class="navbar-actions">
        @auth
        @php
            try { $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->whereNull('read_at')->count(); }
            catch (\Exception $e) { $unreadCount = 0; }
            $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
            $chatConv = \App\Models\ChatConversation::with('messages')->where('user_id', auth()->id())->first();
            $unreadChat = $chatConv ? $chatConv->messages->where('sender_type', '!=', 'user')->where('is_read', false)->count() : 0;
            $lastMsgId = $chatConv ? ($chatConv->messages->max('id') ?? 0) : 0;
        @endphp
        <a href="{{ route('cart.index') }}" class="icon-btn">
            <i class="fas fa-shopping-cart"></i>
            @if($cartCount > 0)<span class="badge">{{ $cartCount }}</span>@endif
        </a>
        <div class="dropdown">
            <div class="profile-pill">
                <div class="profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <span>{{ auth()->user()->name }}</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="dropdown-menu">
                <div class="dropdown-user-info">
                    <div class="name">{{ auth()->user()->name }}</div>
                    <div class="email">{{ auth()->user()->email }}</div>
                </div>
                <a href="{{ route('orders.index') }}"><i class="fas fa-shopping-bag"></i> Riwayat Pesanan</a>
                <a href="{{ route('wishlist.index') }}"><i class="fas fa-heart"></i> Wishlist Saya</a>
                <a href="{{ route('preorders.index') }}"><i class="fas fa-hourglass-half"></i> Pre-Order Saya</a>
                <a href="{{ route('notifications.index') }}">
                    <i class="fas fa-bell"></i> Notifikasi
                    @if($unreadCount > 0)<span class="notif-badge">{{ $unreadCount }}</span>@endif
                </a>
                <a href="{{ route('profile.index') }}"><i class="fas fa-user-edit"></i> Profil Saya</a>
                @if(auth()->user()->role === 'admin')
                <hr>
                <a href="{{ route('admin.dashboard') }}" style="color:var(--gold);"><i class="fas fa-tachometer-alt" style="color:var(--gold);"></i> Dashboard Admin</a>
                @endif
                <hr>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Keluar</button>
                </form>
            </div>
        </div>
        @else
        <a href="{{ route('login') }}" class="btn btn-outline">Masuk</a>
        <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
        @endauth
    </div>
    <div class="nav-overlay" id="navOverlay" onclick="closeMenu()"></div>
    <button class="hamburger" id="hamburgerBtn" onclick="toggleMenu()">
        <i class="fas fa-bars"></i>
    </button>
</nav>

<div class="container" style="padding-top:1rem">
    @if(session('success'))<div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>@endif
</div>

@yield('content')

<footer>
    <div class="footer-grid">
        <div>
            <div class="footer-brand">
                <svg width="24" height="24" viewBox="0 0 30 30" style="flex-shrink:0;">
                    <path d="M15 2 L26 22 L20 22 L20 27 L10 27 L10 22 L4 22 Z" fill="#D4A24E"/>
                    <path d="M15 6 L15 22" stroke="#2C1B33" stroke-width="1.5"/>
                    <path d="M9 22 Q15 17 21 22" stroke="#2C1B33" stroke-width="1.5" fill="none"/>
                </svg>
                Pustaka <span>Nusantara</span>
            </div>
            <p class="footer-desc">Temukan buku impianmu di sini. Koleksi lengkap dari berbagai genre untuk semua kalangan.</p>
        </div>
        <div>
            <div class="footer-title">Navigasi</div>
            <ul class="footer-links">
                <li><a href="{{ route('home') }}">Beranda</a></li>
                <li><a href="{{ route('products.index') }}">Katalog</a></li>
                <li><a href="{{ route('articles.index') }}">Artikel</a></li>
            </ul>
        </div>
        <div>
            <div class="footer-title">Akun</div>
            <ul class="footer-links">
                <li><a href="{{ route('login') }}">Masuk</a></li>
                <li><a href="{{ route('register') }}">Daftar</a></li>
                <li><a href="{{ route('orders.index') }}">Pesanan Saya</a></li>
                <li><a href="{{ route('wishlist.index') }}">Wishlist</a></li>
            </ul>
        </div>
        <div>
            <div class="footer-title">Kontak</div>
            <ul class="footer-links">
                <li><a href="#">📧 hello@pustakanusantara.com</a></li>
                <li><a href="#">📱 +62 812 3456 7890</a></li>
                <li><a href="#">📍 Surabaya, Jawa Timur</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <span>© 2026 Pustaka Nusantara. All rights reserved.</span>
        <span></span>
    </div>
</footer>

{{-- BOTTOM NAV — tampil di semua halaman mobile --}}
<nav class="bottom-nav">
    <div class="bottom-nav-inner">
        <a href="{{ route('home') }}" class="bottom-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="fas fa-home"></i><span>Home</span>
        </a>
        <a href="{{ route('products.index') }}" class="bottom-nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i><span>Katalog</span>
        </a>
        @auth
        <a href="{{ route('cart.index') }}" class="bottom-nav-item {{ request()->routeIs('cart.*') ? 'active' : '' }}">
            <i class="fas fa-shopping-cart"></i>
            @if($cartCount > 0)<span class="bnav-badge">{{ $cartCount }}</span>@endif
            <span>Keranjang</span>
        </a>
        <a href="{{ route('preorders.index') }}" class="bottom-nav-item {{ request()->routeIs('preorders.*') ? 'active' : '' }}">
            <i class="fas fa-clock"></i><span>Preorder</span>
        </a>
        <a href="{{ route('profile.index') }}" class="bottom-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="fas fa-user"></i><span>Profil</span>
        </a>
        @else
        <a href="{{ route('articles.index') }}" class="bottom-nav-item {{ request()->routeIs('articles.*') ? 'active' : '' }}">
            <i class="fas fa-newspaper"></i><span>Artikel</span>
        </a>
        <a href="{{ route('login') }}" class="bottom-nav-item {{ request()->routeIs('login') ? 'active' : '' }}">
            <i class="fas fa-sign-in-alt"></i><span>Masuk</span>
        </a>
        <a href="{{ route('register') }}" class="bottom-nav-item {{ request()->routeIs('register') ? 'active' : '' }}">
            <i class="fas fa-user-plus"></i><span>Daftar</span>
        </a>
        @endauth
    </div>
</nav>

@auth
{{-- CHAT WIDGET --}}
<div id="chat-widget">
    <button id="chat-toggle" onclick="toggleChat()" aria-label="Buka Live Chat">
        <i class="fas fa-comments" id="chat-icon-open"></i>
        <i class="fas fa-times" id="chat-icon-close" style="display:none;"></i>
        <span class="chat-notif-dot" id="chat-notif-dot" style="{{ $unreadChat > 0 ? '' : 'display:none;' }}">{{ $unreadChat }}</span>
    </button>
    <div id="chat-panel">
        <div id="chat-header">
            <div style="display:flex;align-items:center;gap:0.6rem;">
                <div style="width:34px;height:34px;border-radius:50%;background:var(--gold);display:flex;align-items:center;justify-content:center;font-size:1rem;">💬</div>
                <div>
                    <div style="font-weight:700;font-size:0.875rem;color:white;">Live Chat</div>
                    <div style="font-size:0.7rem;color:rgba(255,255,255,0.6);" id="chat-status-label">
                        {{ $chatConv && $chatConv->status === 'open' ? 'Admin siap membantu' : 'Asisten Virtual' }}
                    </div>
                </div>
            </div>
            <button onclick="toggleChat()" style="background:none;border:none;color:white;cursor:pointer;font-size:1rem;padding:0.25rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="chat-messages">
            @if(!$chatConv || $chatConv->messages->count() === 0)
                <div class="chat-bubble-row">
                    <div class="chat-avatar bot">🤖</div>
                    <div>
                        <div class="chat-bubble bot-bubble">Halo! Ada yang bisa saya bantu? 😊</div>
                        <div class="chat-bubble-meta">Bot</div>
                    </div>
                </div>
            @else
                @foreach($chatConv->messages as $msg)
                @php $isMine = $msg->sender_type === 'user'; @endphp
                <div class="chat-bubble-row {{ $isMine ? 'mine' : '' }}">
                    <div class="chat-avatar {{ $msg->sender_type }}">
                        {{ $msg->sender_type === 'bot' ? '🤖' : ($msg->sender_type === 'admin' ? 'A' : 'U') }}
                    </div>
                    <div>
                        <div class="chat-bubble {{ $isMine ? 'user-bubble' : 'bot-bubble' }}">{{ $msg->message }}</div>
                        <div class="chat-bubble-meta">{{ $msg->created_at->format('H:i') }}</div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
        <div id="chat-input-area">
            <input type="text" id="chat-input" placeholder="Ketik pesan..." autocomplete="off"
                onkeydown="if(event.key==='Enter') sendChatMessage()">
            <button onclick="sendChatMessage()" id="chat-send-btn">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>
@endauth

<script>
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => {
            el.style.transition='all 0.5s ease';el.style.opacity='0';
            el.style.transform='translateY(-10px)';
            setTimeout(() => el.remove(), 500);
        });
    }, 4000);
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(el => { if(el.isIntersecting) el.target.classList.add('visible'); });
    },{threshold:0.1});
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

    @auth
    let chatOpen=false,chatLastId={{ $lastMsgId }},chatPollInterval=null;
    function toggleChat(){
        chatOpen=!chatOpen;
        const panel=document.getElementById('chat-panel');
        const io=document.getElementById('chat-icon-open');
        const ic=document.getElementById('chat-icon-close');
        if(chatOpen){panel.classList.add('open');io.style.display='none';ic.style.display='block';
            document.getElementById('chat-notif-dot').style.display='none';
            scrollChatToBottom();startChatPolling();
        }else{panel.classList.remove('open');io.style.display='block';ic.style.display='none';stopChatPolling();}
    }
    function scrollChatToBottom(){const b=document.getElementById('chat-messages');if(b)b.scrollTop=b.scrollHeight;}
    function sendChatMessage(){
        const input=document.getElementById('chat-input');const msg=input.value.trim();if(!msg)return;
        input.value='';appendChatMsg('user',msg,'U');showChatTyping();
        fetch('{{ route('chat.store') }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({message:msg})})
        .then(r=>r.json()).then(()=>{removeChatTyping();pollChatMessages();}).catch(()=>removeChatTyping());
    }
    function appendChatMsg(type,text,avatar){
        const box=document.getElementById('chat-messages');const isMine=type==='user';
        const div=document.createElement('div');div.className='chat-bubble-row'+(isMine?' mine':'');
        div.innerHTML=`<div class="chat-avatar ${type}">${avatar}</div><div><div class="chat-bubble ${isMine?'user-bubble':'bot-bubble'}">${escapeChat(text)}</div><div class="chat-bubble-meta">${new Date().toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'})}</div></div>`;
        box.appendChild(div);scrollChatToBottom();
    }
    function showChatTyping(){const box=document.getElementById('chat-messages');const div=document.createElement('div');div.className='chat-bubble-row';div.id='chat-typing';div.innerHTML=`<div class="chat-avatar bot">🤖</div><div class="chat-typing"><span></span><span></span><span></span></div>`;box.appendChild(div);scrollChatToBottom();}
    function removeChatTyping(){const t=document.getElementById('chat-typing');if(t)t.remove();}
    function pollChatMessages(){
        fetch(`{{ route('chat.poll') }}?after_id=${chatLastId}`).then(r=>r.json()).then(data=>{
            if(data.messages&&data.messages.length>0){data.messages.forEach(msg=>{if(msg.sender_type!=='user'){const av=msg.sender_type==='bot'?'🤖':'A';appendChatMsg(msg.sender_type,msg.message,av);}chatLastId=Math.max(chatLastId,msg.id);});}
            if(data.status){const lbl=document.getElementById('chat-status-label');if(lbl)lbl.textContent=data.status==='open'?'Admin siap membantu':'Asisten Virtual';}
        });
    }
    function startChatPolling(){if(chatPollInterval)return;chatPollInterval=setInterval(pollChatMessages,5000);}
    function stopChatPolling(){if(chatPollInterval){clearInterval(chatPollInterval);chatPollInterval=null;}}
    function escapeChat(text){return text.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}
    function toggleMenu(){const m=document.getElementById('navMenu');const o=document.getElementById('navOverlay');m.classList.toggle('mobile-open');o.classList.toggle('show');document.body.style.overflow=m.classList.contains('mobile-open')?'hidden':'';}
    function closeMenu(){const m=document.getElementById('navMenu');const o=document.getElementById('navOverlay');m.classList.remove('mobile-open');o.classList.remove('show');document.body.style.overflow='';}
    @endauth
</script>
@stack('scripts')
</body>
</html>