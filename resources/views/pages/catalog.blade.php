@extends('layouts.app')
@section('title', 'Katalog Buku')
@push('styles')
<style>
    .page-header{background: linear-gradient(135deg,#3D2645 0%,#5B4B8A 100%);padding:3rem 2rem;color:white;text-align:center;}
    .page-header-title{font-family:'Playfair Display',serif;font-size:clamp(2rem,4vw,3rem);font-weight:900;margin-bottom:0.5rem;}
    .page-header-sub{opacity:0.8;font-size:0.95rem;}
    .catalog-layout{display:grid;grid-template-columns:260px 1fr;gap:2rem;padding:3rem 0;}
    .sidebar{background:white;border-radius:16px;padding:1.5rem;box-shadow:var(--shadow);height:fit-content;position:sticky;top:90px;}
    .sidebar-title{font-weight:700;color:var(--magenta);font-size:0.9rem;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:1rem;padding-bottom:0.75rem;border-bottom:2px solid var(--magenta);}
    .filter-item{display:flex;align-items:center;gap:0.75rem;padding:0.5rem 0;cursor:pointer;transition:var(--transition);}
    .filter-item:hover{color:var(--gold);}
    .filter-item input{accent-color:var(--magenta);width:16px;height:16px;cursor:pointer;}
    .filter-item label{font-size:0.875rem;cursor:pointer;flex:1;}
    .filter-count{background:var(--mist-dark);color:var(--text-muted);font-size:0.7rem;font-weight:700;padding:0.15rem 0.5rem;border-radius:50px;}
    .search-box{position:relative;margin-bottom:1.5rem;}
    .search-box input{width:100%;padding:0.75rem 1rem 0.75rem 2.75rem;border:1.5px solid var(--mist-dark);border-radius:50px;font-family:'Plus Jakarta Sans',sans-serif;font-size:0.875rem;transition:var(--transition);}
    .search-box input:focus{outline:none;border-color:var(--gold);box-shadow:0 0 0 3px rgba(212,162,78,0.18);}
    .search-box i{position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:var(--text-muted);}
    .product-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1.5rem;}
    .product-card{background:white;border-radius:16px;overflow:hidden;box-shadow:var(--shadow);transition:var(--transition);position:relative;display:block;text-decoration:none;color:inherit;cursor:pointer;}
    .product-card:hover{transform:translateY(-6px);box-shadow:var(--shadow-lg);}
    .product-cover{width:100%;height:190px;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;}
    .product-cover img{width:100%;height:100%;object-fit:cover;}
    .product-cover-placeholder{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:2.5rem;}
    .product-category-tag{position:absolute;top:10px;left:10px;background:var(--magenta);color:white;font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:50px;text-transform:uppercase;}

    /* ── BADGES ── */
    .badge-wrap{position:absolute;top:10px;right:10px;display:flex;flex-direction:column;gap:4px;align-items:flex-end;}
    .badge-new{background:#3a8a5c;color:white;font-size:0.62rem;font-weight:800;padding:0.2rem 0.55rem;border-radius:50px;text-transform:uppercase;letter-spacing:0.05em;box-shadow:0 2px 8px rgba(58,138,92,0.4);}
    .badge-best{background:var(--gold);color:var(--plum-dark);font-size:0.62rem;font-weight:800;padding:0.2rem 0.55rem;border-radius:50px;text-transform:uppercase;letter-spacing:0.05em;box-shadow:0 2px 8px rgba(212,162,78,0.4);}

    .product-body{padding:1rem;}
    .product-title{font-family:'Playfair Display',serif;font-size:0.95rem;font-weight:700;color:var(--plum);margin-bottom:0.25rem;line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
    .product-author{font-size:0.75rem;color:var(--text-muted);margin-bottom:0.75rem;}
    .product-footer{display:flex;align-items:center;justify-content:space-between;}
    .product-price{font-weight:800;color:var(--magenta);font-size:0.95rem;}
    .btn-add-cart{background:var(--plum);color:white;border:none;width:32px;height:32px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:var(--transition);font-size:0.8rem;text-decoration:none;position:relative;z-index:2;}
    .btn-add-cart:hover{background:var(--gold);color:var(--plum-dark);transform:scale(1.1);}
    .results-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;}
    .results-count{font-size:0.875rem;color:var(--text-muted);}
    .results-count strong{color:var(--plum);}
    .empty-state{text-align:center;padding:4rem 2rem;background:white;border-radius:16px;box-shadow:var(--shadow);}
    .empty-state-icon{font-size:4rem;margin-bottom:1rem;}
    .empty-state h3{font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--plum);margin-bottom:0.5rem;}
    .empty-state p{color:var(--text-muted);font-size:0.9rem;}
    .pagination-wrap{margin-top:2rem;display:flex;justify-content:center;}
    .pagination-wrap .pagination{display:flex;gap:0.5rem;list-style:none;}
    .pagination-wrap .page-item .page-link{display:flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:50%;background:white;color:var(--plum);text-decoration:none;font-weight:600;font-size:0.875rem;box-shadow:var(--shadow);transition:var(--transition);}
    .pagination-wrap .page-item.active .page-link{background:var(--gold);color:var(--plum-dark);}
    .pagination-wrap .page-item .page-link:hover{background:var(--gold);color:var(--plum-dark);}
    @media(max-width:768px){
        .catalog-layout{grid-template-columns:1fr;}
        .sidebar{position:static;}
        .product-grid{grid-template-columns:repeat(2,1fr);}
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="page-header-title">Katalog Buku</h1>
        <p class="page-header-sub">Temukan buku impianmu dari koleksi kami</p>
    </div>
</div>

<div class="container">
    <div class="catalog-layout">
        <!-- SIDEBAR -->
        <aside class="sidebar reveal">
            <form method="GET" action="{{ route('products.index') }}" id="filterForm">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" placeholder="Cari judul, penulis..." value="{{ request('search') }}">
                </div>

                <div class="sidebar-title">Kategori</div>
                @foreach($categories as $cat)
                <div class="filter-item">
                    <input type="radio" name="category" id="cat{{ $cat->id }}" value="{{ $cat->slug }}"
                        {{ request('category') == $cat->slug ? 'checked' : '' }}
                        onchange="document.getElementById('filterForm').submit()">
                    <label for="cat{{ $cat->id }}">{{ $cat->name }}</label>
                    <span class="filter-count">{{ $cat->products_count }}</span>
                </div>
                @endforeach

                @if(request('category') || request('search'))
                <a href="{{ route('products.index') }}" class="btn btn-outline btn-sm" style="width:100%;justify-content:center;margin-top:1rem;">
                    <i class="fas fa-times"></i> Reset Filter
                </a>
                @endif
            </form>
        </aside>

        <!-- PRODUCTS -->
        <div>
            <div class="results-header reveal">
                <p class="results-count">
                    Menampilkan <strong>{{ $products->total() }} buku</strong>
                    @if(request('search')) untuk "<strong>{{ request('search') }}</strong>" @endif
                    @if(request('category')) dalam kategori "<strong>{{ request('category') }}</strong>" @endif
                </p>
            </div>

            @if($products->count())
            @php
                $colors  = ['#3D2645','#5B4B8A','#8E3B5C','#3a8a5c'];
                $colors2 = ['#5B4B8A','#8E3B5C','#3D2645','#D4A24E'];

                $bestsellerIds = \App\Models\OrderItem::select('product_id')
                    ->selectRaw('SUM(quantity) as total_sold')
                    ->groupBy('product_id')
                    ->orderByDesc('total_sold')
                    ->limit(5)
                    ->pluck('product_id')
                    ->toArray();
            @endphp
            <div class="product-grid">
                @foreach($products as $i => $product)
                @php
                    $isNew  = $product->isNew();
                    $isBest = in_array($product->id, $bestsellerIds);
                @endphp
                <a href="{{ route('products.show', $product->slug) }}" class="product-card reveal" style="transition-delay:{{ ($i%8) * 0.05 }}s">
                    <div class="product-cover">
                        @if($product->cover)
                            <img src="{{ Storage::url($product->cover) }}" alt="{{ $product->title }}">
                        @else
                            <div class="product-cover-placeholder" style="background:linear-gradient(135deg,{{ $colors[$i%4] }},{{ $colors2[$i%4] }});"></div>
                        @endif
                        <span class="product-category-tag">{{ $product->category->name }}</span>

                        {{-- BADGES --}}
                        @if($isNew || $isBest)
                        <div class="badge-wrap">
                            @if($isBest)<span class="badge-best">🔥 Terlaris</span>@endif
                            @if($isNew)<span class="badge-new">✨ Baru</span>@endif
                        </div>
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

            <div class="pagination-wrap">
                {{ $products->withQueryString()->links() }}
            </div>

            @else
            <div class="empty-state reveal">
                <div class="empty-state-icon">🔍</div>
                <h3>Buku Tidak Ditemukan</h3>
                <p>Coba kata kunci lain atau reset filter pencarian.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary" style="margin-top:1.5rem;">Reset Pencarian</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection