@extends('layouts.app')
@section('title', 'Keranjang')
@push('styles')
<style>
    .page-header{background: linear-gradient(135deg, #3a8a5c 0%, #5B4B8A 100%);padding:3rem 2rem;color:white;text-align:center;}
    .page-header-title{font-family:'Playfair Display',serif;font-size:clamp(2rem,4vw,3rem);font-weight:900;margin-bottom:0.5rem;}
    .page-header-sub{opacity:0.8;font-size:0.95rem;}
    .cart-layout{display:grid;grid-template-columns:1fr 360px;gap:2rem;padding:3rem 0;}
    .cart-items{display:flex;flex-direction:column;gap:1rem;}
    .cart-item{background:white;border-radius:16px;padding:1.5rem;box-shadow:var(--shadow);display:flex;gap:1.5rem;align-items:center;transition:var(--transition);}
    .cart-item:hover{box-shadow:var(--shadow-lg);}
    .cart-item-cover{width:80px;height:100px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:2rem;overflow:hidden;}
    .cart-item-cover img{width:100%;height:100%;object-fit:cover;}
    .cart-item-info{flex:1;}
    .cart-item-category{font-size:0.7rem;font-weight:700;color:var(--magenta);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.25rem;}
    .cart-item-title{font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;color:var(--plum);margin-bottom:0.25rem;line-height:1.3;}
    .cart-item-author{font-size:0.8rem;color:var(--text-muted);margin-bottom:0.75rem;}
    .cart-item-price{font-weight:800;color:var(--magenta);font-size:1.1rem;}
    .cart-item-actions{display:flex;align-items:center;gap:1rem;flex-shrink:0;}
    .qty-control{display:flex;align-items:center;gap:0.5rem;background:var(--mist);border-radius:50px;padding:0.25rem;}
    .qty-btn{width:30px;height:30px;border-radius:50%;border:none;background:white;color:var(--plum);font-weight:700;cursor:pointer;transition:var(--transition);display:flex;align-items:center;justify-content:center;box-shadow:0 2px 4px rgba(0,0,0,0.1);}
    .qty-btn:hover{background:var(--gold);color:var(--plum-dark);}
    .qty-num{font-weight:700;color:var(--plum);min-width:24px;text-align:center;font-size:0.95rem;}
    .btn-delete{background:none;border:none;color:#a83333;cursor:pointer;font-size:1rem;padding:0.5rem;border-radius:8px;transition:var(--transition);}
    .btn-delete:hover{background:#FBE5E5;}
    .cart-summary{background:white;border-radius:20px;padding:2rem;box-shadow:var(--shadow);height:fit-content;position:sticky;top:90px;}
    .summary-title{font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:700;color:var(--plum);margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:2px solid var(--mist-dark);}
    .summary-row{display:flex;justify-content:space-between;align-items:center;padding:0.6rem 0;font-size:0.9rem;}
    .summary-row.total{border-top:2px solid var(--mist-dark);margin-top:0.5rem;padding-top:1rem;font-size:1.1rem;}
    .summary-label{color:var(--text-muted);}
    .summary-value{font-weight:700;color:var(--plum);}
    .summary-value.total-price{color:var(--magenta);font-size:1.4rem;font-family:'Playfair Display',serif;}
    .empty-cart{text-align:center;padding:5rem 2rem;background:white;border-radius:20px;box-shadow:var(--shadow);}
    .empty-cart-icon{font-size:5rem;margin-bottom:1rem;}
    @media(max-width:768px){
        .cart-layout{grid-template-columns:1fr;}
        .cart-summary{position:static;}
        .cart-item{flex-wrap:wrap;}
        .cart-item-actions{width:100%;justify-content:space-between;}
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="page-header-title">Keranjang Belanja</h1>
        <p class="page-header-sub">{{ $carts->count() }} item dalam keranjangmu</p>
    </div>
</div>

<div class="container">
    @if($carts->count())
    <div class="cart-layout">
        <!-- ITEMS -->
        <div class="cart-items">
            @php $colors=['#3D2645','#5B4B8A','#8E3B5C','#D4A24E']; @endphp
            @foreach($carts as $i => $cart)
            <div class="cart-item reveal" style="transition-delay:{{ $i * 0.05 }}s">
                <div class="cart-item-cover" style="background:linear-gradient(135deg,{{ $colors[$i%4] }},var(--magenta));">
                    @if($cart->product->cover)
                        <img src="{{ Storage::url($cart->product->cover) }}" alt="{{ $cart->product->title }}">
                    @else
                        📖
                    @endif
                </div>
                <div class="cart-item-info">
                    <div class="cart-item-category">{{ $cart->product->category->name }}</div>
                    <div class="cart-item-title">{{ $cart->product->title }}</div>
                    <div class="cart-item-author">{{ $cart->product->author }}</div>
                    <div class="cart-item-price">Rp {{ number_format($cart->product->price, 0, ',', '.') }}</div>
                </div>
                <div class="cart-item-actions">
                    <!-- QTY -->
                    <form method="POST" action="{{ route('cart.update', $cart->id) }}">
                        @csrf @method('PATCH')
                        <div class="qty-control">
                            <button type="submit" name="quantity" value="{{ max(1, $cart->quantity - 1) }}" class="qty-btn">
                                <i class="fas fa-minus" style="font-size:0.7rem"></i>
                            </button>
                            <span class="qty-num">{{ $cart->quantity }}</span>
                            <button type="submit" name="quantity" value="{{ $cart->quantity + 1 }}" class="qty-btn">
                                <i class="fas fa-plus" style="font-size:0.7rem"></i>
                            </button>
                        </div>
                    </form>
                    <!-- SUBTOTAL -->
                    <div style="text-align:right;">
                        <div style="font-size:0.75rem;color:var(--text-muted);">Subtotal</div>
                        <div style="font-weight:800;color:var(--plum);">Rp {{ number_format($cart->product->price * $cart->quantity, 0, ',', '.') }}</div>
                    </div>
                    <!-- DELETE -->
                    <form method="POST" action="{{ route('cart.destroy', $cart->id) }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-delete" onclick="return confirm('Hapus item ini?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- SUMMARY -->
        <div class="cart-summary reveal">
            <div class="summary-title">Ringkasan Belanja</div>
            <div class="summary-row">
                <span class="summary-label">Total Item</span>
                <span class="summary-value">{{ $carts->sum('quantity') }} buku</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Subtotal</span>
                <span class="summary-value">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Ongkos Kirim</span>
                <span class="summary-value" style="color:#1d6b3f;">Gratis</span>
            </div>
            <div class="summary-row total">
                <span class="summary-label" style="font-weight:700;color:var(--plum);">Total Bayar</span>
                <span class="summary-value total-price">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
            <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;margin-top:1.5rem;">
                <i class="fas fa-credit-card"></i> Lanjut Checkout
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-outline" style="width:100%;justify-content:center;margin-top:0.75rem;">
                <i class="fas fa-arrow-left"></i> Lanjut Belanja
            </a>
        </div>
    </div>

    @else
    <div style="padding:3rem 0;">
        <div class="empty-cart reveal">
            <div class="empty-cart-icon">🛒</div>
            <h2 style="font-family:'Playfair Display',serif;font-size:1.8rem;color:var(--plum);margin-bottom:0.75rem;">Keranjangmu Kosong</h2>
            <p style="color:var(--text-muted);margin-bottom:2rem;">Yuk mulai belanja buku impianmu!</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-book-open"></i> Jelajahi Katalog
            </a>
        </div>
    </div>
    @endif
</div>
@endsection