@extends('layouts.app')
@section('title', $bundle->name)
@push('styles')
<style>
    .bd-hero{background:linear-gradient(135deg,var(--brown) 0%,var(--brown-mid) 100%);padding:2.5rem 0 6rem;position:relative;overflow:hidden;}
    .bd-hero::before{content:'📦';position:absolute;font-size:14rem;opacity:0.06;top:-2rem;right:-2rem;}
    .bd-breadcrumb{color:rgba(255,255,255,0.6);font-size:0.8rem;margin-bottom:1.5rem;}
    .bd-breadcrumb a{color:rgba(255,255,255,0.85);text-decoration:none;}
    .bd-breadcrumb a:hover{color:white;}

    .bd-wrapper{display:grid;grid-template-columns:380px 1fr;gap:2.5rem;margin-top:-4rem;position:relative;z-index:5;}
    @media(max-width:900px){.bd-wrapper{grid-template-columns:1fr;}}

    .bd-cover-card{background:white;border-radius:20px;box-shadow:var(--shadow-lg);overflow:hidden;position:sticky;top:90px;}
    .bd-cover-img{width:100%;height:320px;object-fit:cover;display:block;}
    .bd-cover-placeholder{width:100%;height:320px;background:linear-gradient(135deg,var(--brown),var(--orange));display:flex;align-items:center;justify-content:center;font-size:5rem;}
    .bd-cover-body{padding:1.5rem;}
    .bd-discount-tag{display:inline-flex;align-items:center;gap:0.4rem;background:#FEF2F2;color:#ef4444;font-size:0.75rem;font-weight:800;padding:0.35rem 0.8rem;border-radius:50px;margin-bottom:1rem;}
    .bd-price-old{font-size:0.9rem;color:var(--text-muted);text-decoration:line-through;margin-bottom:0.15rem;}
    .bd-price-new{font-family:'Playfair Display',serif;font-size:1.9rem;font-weight:900;color:var(--orange);margin-bottom:0.25rem;}
    .bd-stock{font-size:0.8rem;color:var(--text-muted);margin-bottom:1.25rem;display:flex;align-items:center;gap:0.4rem;}
    .bd-stock.low{color:#ef4444;font-weight:700;}
    .bd-add-form button{width:100%;padding:1rem;background:var(--orange);color:white;border:none;border-radius:12px;font-weight:700;font-size:0.95rem;cursor:pointer;transition:var(--transition);display:flex;align-items:center;justify-content:center;gap:0.6rem;font-family:'Plus Jakarta Sans',sans-serif;}
    .bd-add-form button:hover{background:var(--orange-light);transform:translateY(-2px);box-shadow:0 8px 24px rgba(232,98,42,0.35);}
    .bd-add-form button:disabled{background:var(--cream-dark);color:var(--text-muted);cursor:not-allowed;transform:none;box-shadow:none;}
    .bd-login-btn{display:block;width:100%;padding:1rem;background:var(--brown);color:white;border-radius:12px;font-weight:700;font-size:0.95rem;text-align:center;text-decoration:none;}

    .bd-content-card{background:white;border-radius:20px;box-shadow:var(--shadow);padding:2rem;margin-bottom:1.5rem;}
    .bd-title{font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:900;color:var(--brown);margin-bottom:0.75rem;line-height:1.3;}
    .bd-desc{color:var(--text-muted);font-size:0.95rem;line-height:1.7;margin-bottom:0;}
    .bd-section-title{font-family:'Playfair Display',serif;font-size:1.15rem;font-weight:700;color:var(--brown);margin-bottom:1.25rem;display:flex;align-items:center;gap:0.6rem;}
    .bd-section-title i{color:var(--orange);}

    .bd-item-list{display:flex;flex-direction:column;gap:1rem;}
    .bd-item-row{display:flex;align-items:center;gap:1rem;padding:1rem;background:var(--cream);border-radius:14px;transition:var(--transition);}
    .bd-item-row:hover{background:var(--cream-dark);}
    .bd-item-cover{width:56px;height:76px;border-radius:8px;object-fit:cover;flex-shrink:0;background:var(--cream-dark);}
    .bd-item-cover-placeholder{width:56px;height:76px;border-radius:8px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:white;}
    .bd-item-info{flex:1;min-width:0;}
    .bd-item-title{font-weight:700;color:var(--brown);font-size:0.9rem;margin-bottom:0.2rem;}
    .bd-item-author{font-size:0.78rem;color:var(--text-muted);margin-bottom:0.3rem;}
    .bd-item-category{display:inline-block;background:white;color:var(--brown-mid);font-size:0.68rem;font-weight:700;padding:0.15rem 0.55rem;border-radius:50px;}
    .bd-item-meta{text-align:right;flex-shrink:0;}
    .bd-item-qty{font-size:0.75rem;color:var(--text-muted);margin-bottom:0.2rem;}
    .bd-item-price{font-weight:700;color:var(--brown);font-size:0.88rem;}

    .bd-savings-banner{background:linear-gradient(135deg,#ECFDF5,#D1FAE5);border:1.5px solid #A7F3D0;border-radius:14px;padding:1.25rem;display:flex;align-items:center;gap:1rem;margin-top:1.5rem;}
    .bd-savings-icon{width:44px;height:44px;background:#10B981;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:1.2rem;flex-shrink:0;}
    .bd-savings-text strong{color:#065F46;font-size:1rem;}
    .bd-savings-text span{color:#047857;font-size:0.82rem;}
</style>
@endpush

@section('content')
@php
    $itemsTotal = $bundle->items->sum(fn($i) => ($i->product->price ?? 0) * $i->quantity);
    $savings = max($bundle->original_price - $bundle->bundle_price, 0);
    $discountPct = $bundle->original_price > 0 ? round(($savings / $bundle->original_price) * 100) : 0;
    $colors = ['#2D4A5E','#E8622A','#2D5E4A','#C9A84C','#4A90B8'];
@endphp

<!-- HERO / BREADCRUMB -->
<div class="bd-hero">
    <div class="container">
        <div class="bd-breadcrumb">
            <a href="{{ route('home') }}">Beranda</a> /
            <a href="{{ route('bundles.index') }}">Bundle</a> /
            <span style="color:white;">{{ $bundle->name }}</span>
        </div>
    </div>
</div>

<div class="container">
    <div class="bd-wrapper">

        <!-- LEFT: COVER & PURCHASE -->
        <div class="bd-cover-card reveal">
            @if($bundle->image)
                <img src="{{ Storage::url($bundle->image) }}" alt="{{ $bundle->name }}" class="bd-cover-img">
            @else
                <div class="bd-cover-placeholder">📦</div>
            @endif

            <div class="bd-cover-body">
                @if($discountPct > 0)
                    <div class="bd-discount-tag"><i class="fas fa-tag"></i> Hemat {{ $discountPct }}%</div>
                @endif

                @if($bundle->original_price > $bundle->bundle_price)
                    <div class="bd-price-old">Rp {{ number_format($bundle->original_price, 0, ',', '.') }}</div>
                @endif
                <div class="bd-price-new">Rp {{ number_format($bundle->bundle_price, 0, ',', '.') }}</div>

                <div class="bd-stock {{ $bundle->stock <= 5 ? 'low' : '' }}">
                    <i class="fas fa-box"></i>
                    @if($bundle->stock > 0)
                        Stok tersedia: {{ $bundle->stock }}
                    @else
                        Stok habis
                    @endif
                </div>

                @auth
                    <form class="bd-add-form" method="POST" action="{{ route('bundles.addToCart', $bundle) }}">
                        @csrf
                        <button type="submit" {{ $bundle->stock <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-shopping-cart"></i>
                            {{ $bundle->stock > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="bd-login-btn">
                        <i class="fas fa-sign-in-alt"></i> Masuk untuk Membeli
                    </a>
                @endauth
            </div>
        </div>

        <!-- RIGHT: DETAILS -->
        <div>
            <div class="bd-content-card reveal">
                <h1 class="bd-title">{{ $bundle->name }}</h1>
                @if($bundle->description)
                    <p class="bd-desc">{{ $bundle->description }}</p>
                @endif
            </div>

            <div class="bd-content-card reveal">
                <h2 class="bd-section-title"><i class="fas fa-book-open"></i> Isi Paket Bundle ({{ $bundle->items->count() }} Buku)</h2>
                <div class="bd-item-list">
                    @foreach($bundle->items as $i => $item)
                    <div class="bd-item-row">
                        @if($item->product?->cover)
                            <img src="{{ Storage::url($item->product->cover) }}" class="bd-item-cover" alt="">
                        @else
                            <div class="bd-item-cover-placeholder" style="background:{{ $colors[$i % count($colors)] }};">📖</div>
                        @endif
                        <div class="bd-item-info">
                            <div class="bd-item-title">{{ $item->product->title ?? 'Produk tidak tersedia' }}</div>
                            <div class="bd-item-author">{{ $item->product->author ?? '-' }}</div>
                            @if($item->product?->category)
                                <span class="bd-item-category">{{ $item->product->category->name }}</span>
                            @endif
                        </div>
                        <div class="bd-item-meta">
                            <div class="bd-item-qty">{{ $item->quantity }}x</div>
                            <div class="bd-item-price">Rp {{ number_format(($item->product->price ?? 0) * $item->quantity, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($savings > 0)
                <div class="bd-savings-banner">
                    <div class="bd-savings-icon"><i class="fas fa-piggy-bank"></i></div>
                    <div class="bd-savings-text">
                        <strong>Kamu hemat Rp {{ number_format($savings, 0, ',', '.') }}!</strong><br>
                        <span>Dibanding beli satuan seharga Rp {{ number_format($bundle->original_price, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- RELATED BUNDLES -->
@php
    $related = \App\Models\Bundle::with('items')
        ->where('is_active', true)
        ->where('id', '!=', $bundle->id)
        ->where('stock', '>', 0)
        ->latest()->take(3)->get();
@endphp
@if($related->isNotEmpty())
<section class="section">
    <div class="container">
        <div class="reveal" style="margin-bottom:1.5rem;">
            <p style="font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--orange);margin-bottom:0.5rem;">Lainnya</p>
            <h2 class="section-title">Bundle Lain yang Mungkin Kamu Suka</h2>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1.5rem;">
            @foreach($related as $rb)
            <a href="{{ route('bundles.show', $rb->slug) }}" style="text-decoration:none;color:inherit;background:white;border-radius:16px;overflow:hidden;box-shadow:var(--shadow);transition:var(--transition);display:block;" class="reveal">
                <div style="height:160px;background:linear-gradient(135deg,var(--brown),var(--orange));display:flex;align-items:center;justify-content:center;font-size:2.5rem;overflow:hidden;">
                    @if($rb->image)
                        <img src="{{ Storage::url($rb->image) }}" style="width:100%;height:100%;object-fit:cover;" alt="">
                    @else
                        📦
                    @endif
                </div>
                <div style="padding:1rem;">
                    <h3 style="font-family:'Playfair Display',serif;font-size:0.95rem;font-weight:700;color:var(--brown);margin-bottom:0.4rem;">{{ $rb->name }}</h3>
                    <div style="font-weight:800;color:var(--orange);">Rp {{ number_format($rb->bundle_price, 0, ',', '.') }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(el => { if(el.isIntersecting) el.target.classList.add('visible'); });
    }, {threshold:0.1});
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>
@endpush