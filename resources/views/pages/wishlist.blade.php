@extends('layouts.app')
@section('title', 'Wishlist Saya')
@push('styles')
<style>
    .page-header{background: linear-gradient(135deg,#3D2645 0%,#5B4B8A 100%);padding:3rem 2rem;color:white;text-align:center;}
    .page-header-title{font-family:'Playfair Display',serif;font-size:clamp(2rem,4vw,3rem);font-weight:900;margin-bottom:0.5rem;}
    .wishlist-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1.5rem;padding:3rem 0;}
    .wishlist-card{background:white;border-radius:16px;overflow:hidden;box-shadow:var(--shadow);transition:var(--transition);position:relative;}
    .wishlist-card:hover{transform:translateY(-6px);box-shadow:var(--shadow-lg);}
    .wishlist-cover{height:200px;display:flex;align-items:center;justify-content:center;font-size:3rem;position:relative;}
    .wishlist-cover img{width:100%;height:100%;object-fit:cover;}
    .wishlist-remove{position:absolute;top:10px;right:10px;background:rgba(168,51,51,0.9);color:white;border:none;width:32px;height:32px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:var(--transition);font-size:0.8rem;}
    .wishlist-remove:hover{background:#8a2c2c;transform:scale(1.1);}
    .wishlist-body{padding:1.25rem;}
    .wishlist-category{font-size:0.7rem;font-weight:700;color:var(--magenta);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.35rem;}
    .wishlist-title{font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;color:var(--plum);margin-bottom:0.25rem;line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
    .wishlist-author{font-size:0.8rem;color:var(--text-muted);margin-bottom:1rem;}
    .wishlist-footer{display:flex;align-items:center;justify-content:space-between;}
    .wishlist-price{font-weight:800;color:var(--magenta);font-size:1rem;}
    .empty-state{text-align:center;padding:5rem 2rem;}
    .empty-state-icon{font-size:5rem;margin-bottom:1rem;}
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="page-header-title">Wishlist Saya</h1>
        <p style="opacity:0.8;font-size:0.95rem;">{{ $wishlists->count() }} buku tersimpan</p>
    </div>
</div>

<div class="container">
    @if($wishlists->count())
    @php $colors=['#3D2645','#5B4B8A','#8E3B5C','#D4A24E']; $colors2=['#5B4B8A','#8E3B5C','#3a8a5c','#3D2645']; @endphp
    <div class="wishlist-grid">
        @foreach($wishlists as $i => $wish)
        <div class="wishlist-card reveal" style="transition-delay:{{ $i * 0.05 }}s">
            <div class="wishlist-cover" style="background:linear-gradient(135deg,{{ $colors[$i%4] }},{{ $colors2[$i%4] }})">
                @if($wish->product->cover)
                    <img src="{{ Storage::url($wish->product->cover) }}" alt="{{ $wish->product->title }}">
                @else
                    📖
                @endif
                <form method="POST" action="{{ route('wishlist.toggle', $wish->product_id) }}">
                    @csrf
                    <button type="submit" class="wishlist-remove" title="Hapus dari wishlist">
                        <i class="fas fa-heart-broken"></i>
                    </button>
                </form>
            </div>
            <div class="wishlist-body">
                <div class="wishlist-category">{{ $wish->product->category->name }}</div>
                <a href="{{ route('products.show', $wish->product->slug) }}" style="text-decoration:none;">
                    <h3 class="wishlist-title">{{ $wish->product->title }}</h3>
                </a>
                <p class="wishlist-author">{{ $wish->product->author }}</p>
                <div class="wishlist-footer">
                    <span class="wishlist-price">Rp {{ number_format($wish->product->price, 0, ',', '.') }}</span>
                    @auth
                    <form method="POST" action="{{ route('cart.store') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $wish->product_id }}">
                        <button type="submit" style="background:var(--plum);color:white;border:none;width:32px;height:32px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:var(--transition);">
                            <i class="fas fa-shopping-cart" style="font-size:0.75rem"></i>
                        </button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @else
    <div class="empty-state reveal">
        <div class="empty-state-icon">💝</div>
        <h2 style="font-family:'Playfair Display',serif;font-size:1.8rem;color:var(--plum);margin-bottom:0.75rem;">Wishlist Kosong</h2>
        <p style="color:var(--text-muted);margin-bottom:2rem;">Simpan buku favorit kamu di sini!</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-book-open"></i> Jelajahi Katalog
        </a>
    </div>
    @endif
</div>
@endsection