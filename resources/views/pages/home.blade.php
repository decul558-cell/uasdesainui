@extends('layouts.app')
@section('title', 'Beranda')
@push('styles')
<style>
    /* HERO - split layout */
    .hero-split{background:var(--plum-dark);padding:3.5rem 0 0;position:relative;overflow:hidden;}
    .hero-split-inner{max-width:1200px;margin:0 auto;padding:0 2rem;display:flex;align-items:flex-end;gap:2.5rem;min-height:480px;}
    .hero-text{flex:1;padding-bottom:3rem;position:relative;z-index:3;}
    .hero-badge-row{display:flex;align-items:center;gap:0.75rem;margin-bottom:1.5rem;animation:fadeUp 0.6s ease both;}
    .hero-brand-badge{background:rgba(212,162,78,0.15);color:var(--gold);border:1px solid rgba(212,162,78,0.4);border-radius:50px;padding:0.45rem 1rem;font-size:0.78rem;font-weight:700;}
    .hero-promo-badge{background:rgba(142,59,92,0.5);color:var(--mist);border-radius:50px;padding:0.4rem 0.9rem;font-size:0.75rem;font-weight:700;}
    .hero-title{font-family:'Playfair Display',serif;font-size:clamp(2.2rem,5vw,3.6rem);font-weight:900;color:var(--mist);line-height:1.1;margin-bottom:1rem;animation:fadeUp 0.6s ease 0.1s both;}
    .hero-title em{font-style:italic;color:var(--gold);}
    .hero-subtitle{color:#d9c9e0;font-size:0.95rem;margin-bottom:1.75rem;max-width:380px;animation:fadeUp 0.6s ease 0.2s both;}
    .hero-actions{display:flex;gap:1rem;flex-wrap:wrap;margin-bottom:1.75rem;animation:fadeUp 0.6s ease 0.3s both;}
    .btn-hero-primary{display:inline-flex;align-items:center;gap:0.7rem;padding:0.9rem 1.8rem;background:var(--gold);color:var(--plum-dark);border-radius:50px;font-weight:800;font-size:0.9rem;text-decoration:none;transition:all 0.3s ease;border:none;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;}
    .btn-hero-primary:hover{background:#e0b264;transform:translateY(-2px);box-shadow:0 8px 24px rgba(212,162,78,0.4);}
    .btn-hero-outline{display:inline-flex;align-items:center;gap:0.7rem;padding:0.9rem 1.8rem;background:rgba(255,255,255,0.06);color:var(--mist);border-radius:50px;font-weight:700;font-size:0.9rem;text-decoration:none;transition:all 0.3s ease;border:1.5px solid rgba(237,230,240,0.25);}
    .btn-hero-outline:hover{background:rgba(255,255,255,0.12);border-color:rgba(237,230,240,0.5);}
    .hero-stats{display:flex;gap:1.75rem;animation:fadeUp 0.6s ease 0.4s both;}
    .hero-stat strong{color:var(--gold);font-size:1.1rem;font-weight:800;display:block;font-family:'Playfair Display',serif;}
    .hero-stat span{color:#9d84a8;font-size:0.72rem;}

    .hero-visual{flex:1;position:relative;height:380px;}
    .hero-visual-img-wrap{position:absolute;right:0;bottom:0;width:88%;height:340px;border-radius:18px 18px 0 0;overflow:hidden;box-shadow:-24px 0 60px rgba(0,0,0,0.45);}
    .hero-visual-img-wrap img{width:100%;height:100%;object-fit:cover;filter:saturate(1.1) contrast(1.03);}
    .hero-visual-overlay{position:absolute;inset:0;background:linear-gradient(to left,transparent 55%,rgba(44,27,51,0.6));}
    .hero-float-card{position:absolute;left:0;top:24px;background:var(--plum);border:1px solid rgba(212,162,78,0.3);border-radius:12px;padding:0.7rem 1rem;box-shadow:0 12px 30px rgba(0,0,0,0.4);z-index:4;}
    .hero-float-card .rating{color:var(--gold);font-size:0.85rem;font-weight:800;}
    .hero-float-card .label{color:#d9c9e0;font-size:0.68rem;display:block;margin-top:0.1rem;}

    @keyframes fadeUp{from{opacity:0;transform:translateY(24px);}to{opacity:1;transform:translateY(0);}}

    /* INFO CARDS */
    .info-cards{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;max-width:1200px;margin:0 auto;padding:2.5rem 2rem;}
    .info-card{background:white;border-radius:16px;padding:1.5rem;box-shadow:var(--shadow);display:flex;align-items:center;gap:1rem;transition:var(--transition);}
    .info-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg);}
    .info-card-icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;}
    .info-card-title{font-weight:800;color:var(--plum);font-size:0.95rem;margin-bottom:0.15rem;}
    .info-card-sub{font-size:0.78rem;color:var(--text-muted);}

    /* CATEGORIES */
    .categories-scroll{display:flex;gap:1rem;overflow-x:auto;padding-bottom:1rem;scrollbar-width:none;}
    .categories-scroll::-webkit-scrollbar{display:none;}
    .category-chip{display:inline-flex;align-items:center;gap:0.5rem;padding:0.6rem 1.25rem;background:white;border:1.5px solid var(--mist-dark);border-radius:50px;font-size:0.875rem;font-weight:600;color:var(--text-muted);text-decoration:none;white-space:nowrap;transition:var(--transition);flex-shrink:0;}
    .category-chip:hover,.category-chip.active{background:var(--plum);color:white;border-color:var(--plum);transform:translateY(-2px);}

    /* PRODUCT GRID */
    .product-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1.5rem;margin-top:2rem;}
    .product-card{background:white;border-radius:16px;overflow:hidden;box-shadow:var(--shadow);transition:var(--transition);display:block;text-decoration:none;color:inherit;cursor:pointer;}
    .product-card:hover{transform:translateY(-6px);box-shadow:var(--shadow-lg);}
    .product-cover{width:100%;height:200px;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;}
    .product-cover img{width:100%;height:100%;object-fit:cover;}
    .product-cover-placeholder{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:3rem;}
    .product-category-tag{position:absolute;top:10px;left:10px;background:var(--magenta);color:white;font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:50px;text-transform:uppercase;}
    .product-new-tag{position:absolute;top:10px;right:10px;background:#3a8a5c;color:white;font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:50px;}
    .product-body{padding:1.1rem;}
    .product-title{font-family:'Playfair Display',serif;font-size:0.95rem;font-weight:700;color:var(--plum);margin-bottom:0.25rem;line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
    .product-author{font-size:0.75rem;color:var(--text-muted);margin-bottom:0.75rem;}
    .product-footer{display:flex;align-items:center;justify-content:space-between;}
    .product-price{font-weight:800;color:var(--magenta);font-size:0.95rem;}
    .btn-add-cart{background:var(--plum);color:white;border:none;width:32px;height:32px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:var(--transition);font-size:0.8rem;text-decoration:none;position:relative;z-index:2;}
    .btn-add-cart:hover{background:var(--gold);color:var(--plum-dark);transform:scale(1.1);}

    /* BUNDLE GRID */
    .bundle-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1.5rem;margin-top:2rem;}
    .bundle-card{background:white;border-radius:16px;overflow:hidden;box-shadow:var(--shadow);transition:var(--transition);height:100%;text-decoration:none;display:block;color:inherit;}
    .bundle-card:hover{transform:translateY(-6px);box-shadow:var(--shadow-lg);}
    .bundle-cover{height:180px;position:relative;overflow:hidden;}
    .bundle-cover img{width:100%;height:100%;object-fit:cover;}
    .bundle-cover-placeholder{width:100%;height:100%;background:linear-gradient(135deg,var(--plum),var(--magenta));display:flex;align-items:center;justify-content:center;font-size:3rem;}
    .bundle-discount-badge{position:absolute;top:10px;right:10px;background:#a83333;color:white;font-size:0.7rem;font-weight:800;padding:0.3rem 0.6rem;border-radius:50px;}
    .bundle-count-badge{position:absolute;bottom:10px;left:10px;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);color:white;font-size:0.72rem;font-weight:700;padding:0.25rem 0.6rem;border-radius:50px;}
    .bundle-body{padding:1.1rem;}
    .bundle-title{font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;color:var(--plum);margin-bottom:0.5rem;line-height:1.3;}
    .bundle-desc{font-size:0.78rem;color:var(--text-muted);margin-bottom:0.75rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
    .bundle-footer{display:flex;align-items:center;justify-content:space-between;margin-top:0.75rem;}
    .bundle-price-old{font-size:0.75rem;color:var(--text-muted);text-decoration:line-through;}
    .bundle-price-new{font-weight:800;color:var(--magenta);font-size:1rem;}
    .bundle-arrow{background:var(--plum);color:white;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.85rem;flex-shrink:0;transition:var(--transition);}
    .bundle-card:hover .bundle-arrow{background:var(--gold);color:var(--plum-dark);}

    /* ARTICLE */
    .article-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.5rem;margin-top:2rem;}
    .article-card{background:white;border-radius:16px;overflow:hidden;box-shadow:var(--shadow);transition:var(--transition);text-decoration:none;color:inherit;display:block;}
    .article-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg);}
    .article-thumb{height:160px;display:flex;align-items:center;justify-content:center;font-size:3rem;}
    .article-body{padding:1.25rem;}
    .article-date{font-size:0.72rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;}
    .article-title{font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;color:var(--plum);line-height:1.4;margin-bottom:0.5rem;}
    .article-excerpt{font-size:0.8rem;color:var(--text-muted);line-height:1.6;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}

    /* CTA */
    .cta-banner{background:linear-gradient(135deg,var(--indigo) 0%,var(--magenta) 50%,var(--gold) 100%);border-radius:24px;padding:4rem;text-align:center;color:white;position:relative;overflow:hidden;margin:4rem 0;}
    .cta-banner::before{content:'📚';position:absolute;font-size:12rem;opacity:0.08;top:-2rem;left:-2rem;}
    .cta-banner::after{content:'📖';position:absolute;font-size:10rem;opacity:0.08;bottom:-2rem;right:-2rem;}
    .cta-title{font-family:'Playfair Display',serif;font-size:clamp(1.8rem,4vw,2.8rem);font-weight:900;margin-bottom:1rem;}

    /* BOTTOM NAV (mobile) */
    .bottom-nav{display:none;position:fixed;bottom:0;left:0;right:0;background:var(--plum-dark);border-top:1px solid rgba(255,255,255,0.08);padding:0.75rem 0 calc(0.75rem + env(safe-area-inset-bottom));z-index:100;box-shadow:0 -4px 20px rgba(0,0,0,0.3);}
    .bottom-nav-inner{display:flex;justify-content:space-around;align-items:center;max-width:500px;margin:0 auto;}
    .bottom-nav-item{display:flex;flex-direction:column;align-items:center;gap:0.25rem;text-decoration:none;color:#9d84a8;font-size:0.65rem;font-weight:600;padding:0.25rem 1rem;border-radius:8px;transition:var(--transition);}
    .bottom-nav-item.active,.bottom-nav-item:hover{color:var(--gold);}
    .bottom-nav-item i{font-size:1.2rem;}

    .reveal{opacity:0;transform:translateY(30px);transition:all 0.7s cubic-bezier(0.4,0,0.2,1);}
    .reveal.visible{opacity:1;transform:translateY(0);}
    .section-header{display:flex;align-items:flex-end;justify-content:space-between;gap:1rem;flex-wrap:wrap;}

    @media(max-width:900px){
        .hero-split-inner{flex-direction:column;align-items:stretch;min-height:auto;}
        .hero-visual{height:240px;order:-1;}
        .hero-visual-img-wrap{width:100%;height:240px;border-radius:18px;box-shadow:none;}
        .hero-text{padding-bottom:2rem;padding-top:1.5rem;}
    }
    @media(max-width:768px){
        .info-cards{grid-template-columns:1fr;}
        .product-grid{grid-template-columns:repeat(2,1fr);}
        .bundle-grid{grid-template-columns:repeat(2,1fr);}
        .article-grid{grid-template-columns:1fr;}
        .cta-banner{padding:2.5rem 1.5rem;}
        .bottom-nav{display:block;}
        body{padding-bottom:80px;}
        .hero-stats{gap:1.25rem;}
    }
</style>
@endpush

@section('content')

<!-- ANNOUNCEMENT BANNER -->
@php
    $announcement = \App\Models\Announcement::where('is_active', true)
        ->where(function($q){ $q->whereNull('start_at')->orWhere('start_at','<=',now()); })
        ->where(function($q){ $q->whereNull('end_at')->orWhere('end_at','>=',now()); })
        ->latest()->first();
    $annColors = ['info'=>'#5B4B8A','warning'=>'#D4A24E','success'=>'#3a8a5c','danger'=>'#a83333'];
@endphp
@if($announcement)
<div style="background:{{ $annColors[$announcement->type] ?? '#5B4B8A' }};color:white;text-align:center;padding:0.6rem 1rem;font-size:0.85rem;font-weight:600;position:relative;z-index:200;">
    <i class="fas fa-bullhorn"></i> {{ $announcement->title }}: {{ $announcement->message }}
</div>
@endif

<!-- HERO SPLIT LAYOUT -->
<section class="hero-split">
    <div class="hero-split-inner">
        <div class="hero-text">
            <div class="hero-badge-row">
                <span class="hero-brand-badge">✦ Pustaka Terpercaya</span>
                <span class="hero-promo-badge">✨ Gratis Ongkir</span>
            </div>

            <h1 class="hero-title">
                Temukan Buku<br><em>Impianmu</em><br>Di Sini
            </h1>

            <p class="hero-subtitle">Koleksi ribuan buku dari berbagai genre untuk semua kalangan.</p>

            <div class="hero-actions">
                <a href="{{ route('products.index') }}" class="btn-hero-primary">
                    <i class="fas fa-book-open"></i> Mulai Belanja
                </a>
                <a href="{{ route('products.index') }}" class="btn-hero-outline">
                    <i class="fas fa-fire"></i> Lihat Koleksi
                </a>
            </div>

            <div class="hero-stats">
                <div class="hero-stat"><strong>500+</strong><span>Koleksi Buku</span></div>
                <div class="hero-stat"><strong>50+</strong><span>Genre</span></div>
                <div class="hero-stat"><strong>10K+</strong><span>Pembaca</span></div>
            </div>
        </div>

        <div class="hero-visual">
            <div class="hero-visual-img-wrap">
                <img src="/images/hero.jpg" alt="Rak buku Pustaka Nusantara">
                <div class="hero-visual-overlay"></div>
            </div>
            <div class="hero-float-card">
                <span class="rating"><i class="fas fa-star"></i> 4.9</span>
                <span class="label">Rating Pembaca</span>
            </div>
        </div>
    </div>
</section>

<!-- INFO CARDS -->
<div class="info-cards">
    <div class="info-card reveal">
        <div class="info-card-icon" style="background:rgba(212,162,78,0.12);color:var(--gold);">🚚</div>
        <div>
            <div class="info-card-title">Pengiriman Cepat</div>
            <div class="info-card-sub">Estimasi 1-3 hari kerja</div>
        </div>
    </div>
    <div class="info-card reveal" style="transition-delay:0.1s">
        <div class="info-card-icon" style="background:rgba(91,75,138,0.12);color:var(--indigo);">📦</div>
        <div>
            <div class="info-card-title">100% Original</div>
            <div class="info-card-sub">Buku asli bergaransi</div>
        </div>
    </div>
    <div class="info-card reveal" style="transition-delay:0.2s">
        <div class="info-card-icon" style="background:rgba(142,59,92,0.12);color:var(--magenta);">💳</div>
        <div>
            <div class="info-card-title">Bayar Mudah</div>
            <div class="info-card-sub">Transfer, e-wallet, COD</div>
        </div>
    </div>
</div>

<!-- CATEGORIES -->
<section class="section" style="padding-top:1rem;padding-bottom:2rem;">
    <div class="container">
        <div class="reveal">
            <p style="font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--magenta);margin-bottom:0.5rem;">Genre</p>
            <h2 class="section-title">Telusuri Berdasarkan Kategori</h2>
        </div>
        <div class="categories-scroll" style="margin-top:1.5rem;">
            <a href="{{ route('products.index') }}" class="category-chip active">📚 Semua</a>
            @foreach($categories as $cat)
            <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="category-chip">
                {{ $cat->name }}
                <span style="background:var(--mist-dark);padding:0.1rem 0.5rem;border-radius:50px;font-size:0.72rem;">{{ $cat->products_count }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- PRODUCTS -->
<section class="section" style="padding-top:1rem;">
    <div class="container">
        <div class="section-header reveal">
            <div>
                <p style="font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--magenta);margin-bottom:0.5rem;">Koleksi</p>
                <h2 class="section-title">Buku Terbaru</h2>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-outline">Lihat Semua <i class="fas fa-arrow-right"></i></a>
        </div>
        @php
            $colors  = ['#3D2645','#5B4B8A','#8E3B5C','#3a8a5c','#D4A24E','#2C1B33','#5B4B8A','#3D2645'];
            $colors2 = ['#5B4B8A','#8E3B5C','#D4A24E','#5B4B8A','#3D2645','#8E3B5C','#3a8a5c','#5B4B8A'];
        @endphp
        <div class="product-grid">
            @foreach($products as $i => $product)
            <a href="{{ route('products.show', $product->slug) }}" class="product-card reveal" style="transition-delay:{{ ($i%8)*0.05 }}s">
                <div class="product-cover">
                    @if($product->cover)
                        <img src="{{ Storage::url($product->cover) }}" alt="{{ $product->title }}">
                    @else
                        <div class="product-cover-placeholder" style="background:linear-gradient(135deg,{{ $colors[$i%8] }},{{ $colors2[$i%8] }});width:100%;height:100%;"></div>
                    @endif
                    <span class="product-category-tag">{{ $product->category->name }}</span>
                    @if($product->created_at->diffInDays(now()) <= 7)
                        <span class="product-new-tag">BARU</span>
                    @endif
                </div>
                <div class="product-body">
                    <h3 class="product-title">{{ $product->title }}</h3>
                    <p class="product-author">{{ $product->author }}</p>
                    <div class="product-footer">
                        <span class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @auth
                        <form method="POST" action="{{ route('cart.store') }}" onclick="event.stopPropagation()">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn-add-cart"><i class="fas fa-plus"></i></button>
                        </form>
                        @else
                        <a href="{{ route('login') }}" class="btn-add-cart" onclick="event.stopPropagation()"><i class="fas fa-plus"></i></a>
                        @endauth
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- BUNDLES -->
@if($bundles->isNotEmpty())
<section class="section" style="padding-top:1rem;">
    <div class="container">
        <div class="section-header reveal">
            <div>
                <p style="font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--magenta);margin-bottom:0.5rem;">Hemat Lebih Banyak</p>
                <h2 class="section-title">Paket Bundle Pilihan</h2>
            </div>
            <a href="{{ route('bundles.index') }}" class="btn btn-outline">Lihat Semua <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="bundle-grid">
            @foreach($bundles as $i => $bundle)
            <a href="{{ route('bundles.show', $bundle->slug) }}" class="bundle-card reveal" style="transition-delay:{{ $i*0.1 }}s">
                <div class="bundle-cover">
                    @if($bundle->image)
                        <img src="{{ Storage::url($bundle->image) }}" alt="{{ $bundle->name }}">
                    @else
                        <div class="bundle-cover-placeholder">📦</div>
                    @endif

                    @if($bundle->original_price > $bundle->bundle_price)
                        @php $hemat = round((($bundle->original_price - $bundle->bundle_price) / $bundle->original_price) * 100); @endphp
                        <div class="bundle-discount-badge">Hemat {{ $hemat }}%</div>
                    @endif

                    <div class="bundle-count-badge"><i class="fas fa-book"></i> {{ $bundle->items_count }} Buku</div>
                </div>
                <div class="bundle-body">
                    <h3 class="bundle-title">{{ $bundle->name }}</h3>
                    @if($bundle->description)
                        <p class="bundle-desc">{{ $bundle->description }}</p>
                    @endif
                    <div class="bundle-footer">
                        <div>
                            @if($bundle->original_price > $bundle->bundle_price)
                                <div class="bundle-price-old">Rp {{ number_format($bundle->original_price, 0, ',', '.') }}</div>
                            @endif
                            <div class="bundle-price-new">Rp {{ number_format($bundle->bundle_price, 0, ',', '.') }}</div>
                        </div>
                        <div class="bundle-arrow"><i class="fas fa-arrow-right"></i></div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA -->
<section class="container reveal">
    <div class="cta-banner">
        <h2 class="cta-title">Mulai Petualangan Membacamu</h2>
        <p style="opacity:0.9;margin-bottom:2rem;max-width:500px;margin-left:auto;margin-right:auto;">Daftar sekarang dan dapatkan akses ke ribuan koleksi buku pilihan.</p>
        @guest
        <a href="{{ route('register') }}" class="btn-hero-primary"><i class="fas fa-user-plus"></i> Daftar Gratis</a>
        @else
        <a href="{{ route('products.index') }}" class="btn-hero-primary"><i class="fas fa-book-open"></i> Belanja Sekarang</a>
        @endguest
    </div>
</section>

<!-- ARTICLES -->
<section class="section">
    <div class="container">
        <div class="section-header reveal">
            <div>
                <p style="font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--magenta);margin-bottom:0.5rem;">Blog</p>
                <h2 class="section-title">Artikel Terbaru</h2>
            </div>
            <a href="{{ route('articles.index') }}" class="btn btn-outline">Lihat Semua <i class="fas fa-arrow-right"></i></a>
        </div>
        @php
            $bgs  = [
                'linear-gradient(135deg,#5B4B8A,#8E3B5C)',
                'linear-gradient(135deg,#3a8a5c,#5B4B8A)',
                'linear-gradient(135deg,#8E3B5C,#D4A24E)',
            ];
            $icons = ['📚','✍️','💡'];
        @endphp
        <div class="article-grid">
            @foreach($articles as $i => $article)
            <a href="{{ route('articles.show', $article->slug) }}" class="article-card reveal" style="transition-delay:{{ $i*0.1 }}s">
                <div class="article-thumb" style="background:{{ $bgs[$i%3] }}">{{ $icons[$i%3] }}</div>
                <div class="article-body">
                    <div class="article-date">{{ $article->published_at?->format('d M Y') }}</div>
                    <h3 class="article-title">{{ $article->title }}</h3>
                    <p class="article-excerpt">{{ $article->excerpt }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- BOTTOM NAV (Mobile) -->
<nav class="bottom-nav">
    <div class="bottom-nav-inner">
        <a href="{{ route('home') }}" class="bottom-nav-item active">
            <i class="fas fa-home"></i> Home
        </a>
        <a href="{{ route('products.index') }}" class="bottom-nav-item">
            <i class="fas fa-th-large"></i> Katalog
        </a>
        <a href="{{ route('cart.index') }}" class="bottom-nav-item">
            <i class="fas fa-shopping-cart"></i> Keranjang
        </a>
        <a href="{{ route('wishlist.index') }}" class="bottom-nav-item">
            <i class="fas fa-heart"></i> Favorit
        </a>
        <a href="{{ route('profile.index') }}" class="bottom-nav-item">
            <i class="fas fa-user"></i> Profil
        </a>
    </div>
</nav>

@endsection

@push('scripts')
<script>
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(el => { if(el.isIntersecting) el.target.classList.add('visible'); });
    }, {threshold:0.1});
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>
@endpush