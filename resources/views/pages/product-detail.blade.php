@extends('layouts.app')
@section('title', $product->title)
@push('styles')
<style>
    .detail-hero{background: linear-gradient(135deg, #3D2645 0%, #5B4B8A 50%, #8E3B5C 100%);}
    .detail-hero::before{content:'';position:absolute;top:0;right:0;width:60%;height:100%;background:radial-gradient(ellipse at right,rgba(212,162,78,0.12) 0%,transparent 70%);}
    .detail-hero::after{content:'';position:absolute;bottom:0;left:0;width:100%;height:1px;background:linear-gradient(90deg,transparent,rgba(212,162,78,0.3),transparent);}
    .detail-layout{display:grid;grid-template-columns:1fr 420px;gap:5rem;align-items:center;position:relative;z-index:1;}
    .book-category{display:inline-flex;align-items:center;gap:0.5rem;color:#D4A24E;font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.15em;margin-bottom:1.5rem;}
    .book-title{font-family:'Playfair Display',serif;font-size:clamp(2rem,4vw,3.2rem);font-weight:900;color:white;line-height:1.1;margin-bottom:1rem;}
    .book-author{font-size:0.95rem;color:rgba(255,255,255,0.65);margin-bottom:1.5rem;display:flex;align-items:center;gap:0.5rem;}
    .book-author strong{color:var(--gold);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;}
    .book-rating{display:flex;align-items:center;gap:1.5rem;margin-bottom:2rem;}
    .rating-stars{display:flex;align-items:center;gap:0.4rem;color:var(--gold);font-size:0.9rem;}
    .rating-time{display:flex;align-items:center;gap:0.4rem;color:rgba(255,255,255,0.55);font-size:0.875rem;}
    .book-desc{color:rgba(255,255,255,0.75);line-height:1.8;font-size:0.975rem;margin-bottom:1.5rem;max-width:600px;}
    .book-actions{display:flex;gap:1rem;flex-wrap:wrap;margin-top:0.5rem;}
    .btn-buy{display:inline-flex;align-items:center;gap:0.75rem;padding:0.9rem 2rem;background:var(--gold);color:var(--plum-dark);border-radius:8px;font-weight:800;font-size:0.9rem;text-decoration:none;transition:var(--transition);border:none;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;letter-spacing:0.05em;}
    .btn-buy:hover{background:#e0b264;transform:translateY(-2px);box-shadow:0 8px 24px rgba(212,162,78,0.4);}
    .btn-wishlist{display:inline-flex;align-items:center;gap:0.75rem;padding:0.9rem 2rem;background:transparent;color:rgba(255,255,255,0.65);border-radius:8px;font-weight:700;font-size:0.9rem;text-decoration:none;transition:var(--transition);border:1.5px solid rgba(255,255,255,0.25);cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;}
    .btn-wishlist:hover{border-color:rgba(255,255,255,0.5);color:white;}
    .book-meta-strip{display:flex;gap:2rem;margin-bottom:1.5rem;flex-wrap:wrap;}
    .meta-item-label{font-size:0.7rem;color:rgba(255,255,255,0.45);text-transform:uppercase;letter-spacing:0.1em;font-weight:600;}
    .meta-item-value{font-size:0.875rem;color:var(--gold);font-weight:700;margin-top:0.2rem;}
    .price-tag{font-family:'Playfair Display',serif;font-size:2.2rem;font-weight:900;color:var(--gold);margin-bottom:0.5rem;}
    .stock-badge{display:inline-flex;align-items:center;gap:0.4rem;font-size:0.8rem;font-weight:600;padding:0.3rem 0.75rem;border-radius:50px;margin-bottom:1rem;}
    .stock-badge.in-stock{background:rgba(58,138,92,0.18);color:#6fd99a;border:1px solid rgba(58,138,92,0.35);}
    .stock-badge.low-stock{background:rgba(212,162,78,0.18);color:#f0cb8a;border:1px solid rgba(212,162,78,0.35);}
    .stock-badge.out-stock{background:rgba(168,51,51,0.18);color:#f0a8a8;border:1px solid rgba(168,51,51,0.35);}
    .book-cover-wrap{position:relative;display:flex;justify-content:center;}
    .book-cover-frame{position:relative;}
    .book-cover-deco{position:absolute;border:1.5px solid rgba(212,162,78,0.25);border-radius:16px;}
    .book-cover-deco:nth-child(1){width:200px;height:260px;top:30px;right:-20px;}
    .book-cover-deco:nth-child(2){width:160px;height:200px;bottom:-20px;right:10px;}
    .book-cover-deco:nth-child(3){width:80px;height:80px;bottom:40px;right:-30px;}
    .book-cover-img{width:280px;height:380px;border-radius:16px;object-fit:cover;position:relative;z-index:2;box-shadow:0 20px 60px rgba(0,0,0,0.5);}
    .book-cover-placeholder{width:280px;height:380px;border-radius:16px;background:linear-gradient(135deg,var(--plum),var(--magenta));display:flex;align-items:center;justify-content:center;font-size:6rem;position:relative;z-index:2;box-shadow:0 20px 60px rgba(0,0,0,0.5);}
    .related-section{background:var(--mist);padding:5rem 0;border-top:1px solid rgba(61,38,69,0.08);}
    .related-label{font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.15em;color:var(--text-muted);margin-bottom:2rem;}
    .related-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem;}
    .related-card{text-decoration:none;color:inherit;display:block;transition:var(--transition);}
    .related-card:hover{transform:translateY(-6px);}
    .related-cover{width:100%;aspect-ratio:2/3;border-radius:12px;overflow:hidden;margin-bottom:1rem;box-shadow:var(--shadow-lg);}
    .related-cover img{width:100%;height:100%;object-fit:cover;}
    .related-cover-placeholder{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:3rem;}
    .related-title{font-family:'Playfair Display',serif;font-size:0.95rem;font-weight:700;color:var(--plum);line-height:1.3;margin-bottom:0.25rem;}
    .related-author{font-size:0.75rem;color:var(--text-muted);}
    .related-price{font-size:0.875rem;font-weight:700;color:var(--magenta);margin-top:0.35rem;}

    /* ── REVIEW SECTION ── */
    .review-section{padding:5rem 0;background:white;border-top:1px solid var(--mist-dark);}
    .review-summary{display:flex;align-items:center;gap:3rem;background:var(--mist);border-radius:20px;padding:2.5rem;margin-bottom:3rem;flex-wrap:wrap;}
    .review-avg-score{text-align:center;}
    .review-avg-number{font-family:'Playfair Display',serif;font-size:5rem;font-weight:900;color:var(--plum);line-height:1;}
    .review-avg-stars{color:var(--gold);font-size:1.3rem;margin:0.5rem 0;}
    .review-avg-count{font-size:0.85rem;color:var(--text-muted);}
    .review-bars{flex:1;min-width:200px;}
    .review-bar-row{display:flex;align-items:center;gap:0.75rem;margin-bottom:0.5rem;}
    .review-bar-label{font-size:0.8rem;font-weight:600;color:var(--plum);width:12px;text-align:right;}
    .review-bar-track{flex:1;height:8px;background:var(--mist-dark);border-radius:50px;overflow:hidden;}
    .review-bar-fill{height:100%;background:linear-gradient(90deg,var(--gold),var(--magenta));border-radius:50px;transition:width 1s ease;}
    .review-bar-count{font-size:0.75rem;color:var(--text-muted);width:20px;}
    .review-form-wrap{background:var(--mist);border-radius:20px;padding:2rem;margin-bottom:3rem;}
    .review-form-title{font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:900;color:var(--plum);margin-bottom:1.5rem;}
    .star-picker{display:flex;gap:0.5rem;margin-bottom:1.25rem;}
    .star-picker input{display:none;}
    .star-picker label{font-size:2rem;cursor:pointer;color:#cdb8d6;transition:color 0.15s;}
    .star-picker label:hover,.star-picker label.active{color:var(--gold);}
    .review-textarea{width:100%;padding:0.9rem 1.1rem;border:1.5px solid var(--mist-dark);border-radius:12px;font-family:'Plus Jakarta Sans',sans-serif;font-size:0.9rem;color:var(--text);resize:vertical;min-height:100px;transition:var(--transition);}
    .review-textarea:focus{outline:none;border-color:var(--gold);box-shadow:0 0 0 3px rgba(212,162,78,0.18);}
    .review-submit{display:inline-flex;align-items:center;gap:0.5rem;padding:0.75rem 1.75rem;background:var(--magenta);color:white;border:none;border-radius:50px;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:0.9rem;cursor:pointer;transition:var(--transition);margin-top:1rem;}
    .review-submit:hover{background:#a8467a;transform:translateY(-1px);}
    .review-list{display:flex;flex-direction:column;gap:1.5rem;}
    .review-item{background:var(--mist);border-radius:16px;padding:1.5rem;position:relative;}
    .review-item-header{display:flex;align-items:center;gap:1rem;margin-bottom:0.75rem;}
    .review-avatar{width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,var(--plum),var(--indigo));display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:1rem;flex-shrink:0;}
    .review-item-name{font-weight:700;color:var(--plum);font-size:0.9rem;}
    .review-item-date{font-size:0.75rem;color:var(--text-muted);}
    .review-item-stars{color:var(--gold);font-size:0.85rem;margin-top:0.1rem;}
    .review-item-comment{color:var(--text);font-size:0.9rem;line-height:1.7;}
    .review-delete-btn{position:absolute;top:1rem;right:1rem;background:none;border:none;color:#a83333;cursor:pointer;font-size:0.8rem;opacity:0.6;transition:opacity 0.2s;}
    .review-delete-btn:hover{opacity:1;}
    .no-reviews{text-align:center;padding:3rem;color:var(--text-muted);}
    .no-reviews-icon{font-size:3rem;margin-bottom:0.75rem;}

    @media(max-width:768px){
        .detail-layout{grid-template-columns:1fr;}
        .book-cover-wrap{order:-1;}
        .book-cover-img,.book-cover-placeholder{width:200px;height:270px;}
        .related-grid{grid-template-columns:repeat(2,1fr);}
        .review-summary{flex-direction:column;gap:1.5rem;}
    }
</style>
@endpush

@section('content')
<div class="detail-hero">
    <div class="container">
        <div class="detail-layout">
            <div class="book-info reveal">
                <div class="book-category"><i class="fas fa-bookmark"></i> {{ $product->category->name }}</div>
                <h1 class="book-title">{{ $product->title }}</h1>
                <div class="book-author">By <strong>{{ $product->author }}</strong></div>
                <div class="book-rating">
                    @php $avgRating = $product->reviews->avg('rating') ?? 0; @endphp
                    <div class="rating-stars">
                        <i class="fas fa-star"></i>
                        {{ $avgRating > 0 ? number_format($avgRating, 1) : 'Belum ada rating' }}
                        @if($product->reviews->count() > 0)
                            <span style="color:rgba(255,255,255,0.45);font-size:0.8rem;">({{ $product->reviews->count() }} ulasan)</span>
                        @endif
                    </div>
                    @if($product->year)<div class="rating-time"><i class="fas fa-calendar"></i> {{ $product->year }}</div>@endif
                    @if($product->publisher)<div class="rating-time"><i class="fas fa-building"></i> {{ $product->publisher }}</div>@endif
                </div>
                <div class="book-meta-strip">
                    @if($product->isbn)
                    <div class="meta-item"><div class="meta-item-label">ISBN</div><div class="meta-item-value">{{ $product->isbn }}</div></div>
                    @endif
                    <div class="meta-item"><div class="meta-item-label">Stok</div><div class="meta-item-value">{{ $product->stock }} tersisa</div></div>
                    <div class="meta-item"><div class="meta-item-label">Kategori</div><div class="meta-item-value">{{ $product->category->name }}</div></div>
                </div>
                <p class="book-desc">{{ $product->description ?? 'Buku ini merupakan salah satu karya terbaik yang wajib dibaca. Dengan gaya penulisan yang menarik dan konten yang mendalam, buku ini akan memberikan wawasan baru.' }}</p>
                <div class="price-tag">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                @if($product->stock > 5)
                    <div class="stock-badge in-stock"><i class="fas fa-check-circle"></i> Stok Tersedia</div>
                @elseif($product->stock > 0)
                    <div class="stock-badge low-stock"><i class="fas fa-exclamation-circle"></i> Stok Terbatas</div>
                @else
                    <div class="stock-badge out-stock"><i class="fas fa-times-circle"></i> Stok Habis</div>
                @endif
                <div class="book-actions">
                    @auth
                        @if($product->stock > 0)
                        <form method="POST" action="{{ route('cart.store') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn-buy"><i class="fas fa-shopping-cart"></i> BELI SEKARANG</button>
                        </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-buy"><i class="fas fa-shopping-cart"></i> BELI SEKARANG</a>
                    @endauth
                    @auth
<form method="POST" action="{{ route('wishlist.toggle', $product->id) }}">
    @csrf
    @php $inWishlist = auth()->user()->wishlists()->where('product_id', $product->id)->exists(); @endphp
    <button type="submit" class="btn-wishlist">
        <i class="fa{{ $inWishlist ? 's' : 'r' }} fa-heart"></i>
        {{ $inWishlist ? 'HAPUS DARI WISHLIST' : 'ADD TO WISHLIST' }}
    </button>
</form>
@else
<a href="{{ route('login') }}" class="btn-wishlist"><i class="far fa-heart"></i> ADD TO WISHLIST</a>
@endauth
                </div>
            </div>
            <div class="book-cover-wrap reveal">
                <div class="book-cover-frame">
                    <div class="book-cover-deco"></div>
                    <div class="book-cover-deco"></div>
                    <div class="book-cover-deco"></div>
                    @if($product->cover)
                        <img src="{{ Storage::url($product->cover) }}" alt="{{ $product->title }}" class="book-cover-img">
                    @else
                        <div class="book-cover-placeholder">📖</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── REVIEW SECTION ── --}}
<div class="review-section">
    <div class="container">
        <div class="section-header" style="margin-bottom:2.5rem;">
            <h2 class="section-title">Rating & Ulasan</h2>
        </div>

        {{-- Summary --}}
        @php
            $reviews      = $product->reviews()->with('user')->latest()->get();
            $totalReviews = $reviews->count();
            $avgRating    = $totalReviews ? $reviews->avg('rating') : 0;
            $starCounts   = [5=>0, 4=>0, 3=>0, 2=>0, 1=>0];
            foreach($reviews as $r) $starCounts[$r->rating] = ($starCounts[$r->rating] ?? 0) + 1;
            $userReview   = auth()->check() ? $reviews->firstWhere('user_id', auth()->id()) : null;
        @endphp

        @if($totalReviews > 0)
        <div class="review-summary reveal">
            <div class="review-avg-score">
                <div class="review-avg-number">{{ number_format($avgRating, 1) }}</div>
                <div class="review-avg-stars">
                    @for($s=1;$s<=5;$s++)
                        <i class="fa{{ $s <= round($avgRating) ? 's' : 'r' }} fa-star"></i>
                    @endfor
                </div>
                <div class="review-avg-count">{{ $totalReviews }} ulasan</div>
            </div>
            <div class="review-bars">
                @foreach([5,4,3,2,1] as $star)
                @php $pct = $totalReviews ? ($starCounts[$star] / $totalReviews * 100) : 0; @endphp
                <div class="review-bar-row">
                    <div class="review-bar-label">{{ $star }}</div>
                    <div class="review-bar-track"><div class="review-bar-fill" style="width:{{ $pct }}%"></div></div>
                    <div class="review-bar-count">{{ $starCounts[$star] }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Form Ulasan --}}
        @auth
        <div class="review-form-wrap reveal">
            <div class="review-form-title">
                {{ $userReview ? '✏️ Edit Ulasanmu' : '⭐ Tulis Ulasan' }}
            </div>
            <form method="POST" action="{{ route('review.store') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="star-picker" id="starPicker">
                    @for($s=1;$s<=5;$s++)
                    <input type="radio" name="rating" id="star{{ $s }}" value="{{ $s }}"
                        {{ $userReview && $userReview->rating == $s ? 'checked' : '' }}>
                    <label for="star{{ $s }}" class="{{ $userReview && $userReview->rating >= $s ? 'active' : '' }}">★</label>
                    @endfor
                </div>
                <textarea name="comment" class="review-textarea"
                    placeholder="Ceritakan pendapatmu tentang buku ini..."
                    maxlength="500">{{ $userReview->comment ?? '' }}</textarea>
                <button type="submit" class="review-submit">
                    <i class="fas fa-paper-plane"></i>
                    {{ $userReview ? 'Update Ulasan' : 'Kirim Ulasan' }}
                </button>
            </form>
        </div>
        @else
        <div style="background:var(--mist);border-radius:16px;padding:1.5rem;text-align:center;margin-bottom:2rem;">
            <p style="color:var(--text-muted);margin-bottom:1rem;">Login untuk menulis ulasan</p>
            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Masuk Sekarang</a>
        </div>
        @endauth

        {{-- List Ulasan --}}
        @if($totalReviews > 0)
        <div class="review-list">
            @foreach($reviews as $review)
            <div class="review-item reveal">
                <div class="review-item-header">
                    <div class="review-avatar">{{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}</div>
                    <div>
                        <div class="review-item-name">{{ $review->user->name ?? 'Pengguna' }}</div>
                        <div class="review-item-stars">
                            @for($s=1;$s<=5;$s++)<i class="fa{{ $s <= $review->rating ? 's' : 'r' }} fa-star"></i>@endfor
                        </div>
                        <div class="review-item-date">{{ $review->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @if($review->comment)
                <p class="review-item-comment">{{ $review->comment }}</p>
                @endif
                @auth
                @if(auth()->id() === $review->user_id)
                <form method="POST" action="{{ route('review.destroy', $review->id) }}" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="review-delete-btn" onclick="return confirm('Hapus ulasan ini?')">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
                @endif
                @endauth
            </div>
            @endforeach
        </div>
        @else
        <div class="no-reviews reveal">
            <div class="no-reviews-icon">💬</div>
            <p>Belum ada ulasan. Jadilah yang pertama!</p>
        </div>
        @endif
    </div>
</div>

@if($related->count())
<div class="related-section">
    <div class="container">
        <div class="related-label">REKOMENDASI BUKU SERUPA</div>
        <div class="related-grid">
            @php
                $bgs = [
                    'linear-gradient(135deg,#3D2645,#5B4B8A)',
                    'linear-gradient(135deg,#5B4B8A,#8E3B5C)',
                    'linear-gradient(135deg,#8E3B5C,#D4A24E)',
                    'linear-gradient(135deg,#3a8a5c,#5B4B8A)',
                ];
            @endphp
            @foreach($related as $i => $rel)
            <a href="{{ route('products.show', $rel->slug) }}" class="related-card reveal" style="transition-delay:{{ $i * 0.1 }}s">
                <div class="related-cover">
                    @if($rel->cover)
                        <img src="{{ Storage::url($rel->cover) }}" alt="{{ $rel->title }}">
                    @else
                        <div class="related-cover-placeholder" style="background:{{ $bgs[$i%4] }}">📖</div>
                    @endif
                </div>
                <div class="related-title">{{ $rel->title }}</div>
                <div class="related-author">{{ $rel->author }}</div>
                <div class="related-price">Rp {{ number_format($rel->price, 0, ',', '.') }}</div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
// Star picker interaktif
const labels = document.querySelectorAll('#starPicker label');
const inputs = document.querySelectorAll('#starPicker input');
labels.forEach((label, idx) => {
    label.addEventListener('mouseover', () => {
        labels.forEach((l, i) => l.style.color = i <= idx ? '#D4A24E' : '#cdb8d6');
    });
    label.addEventListener('mouseout', () => {
        const checked = document.querySelector('#starPicker input:checked');
        const checkedIdx = checked ? parseInt(checked.value) - 1 : -1;
        labels.forEach((l, i) => l.style.color = i <= checkedIdx ? '#D4A24E' : '#cdb8d6');
    });
    label.addEventListener('click', () => {
        labels.forEach((l, i) => {
            l.classList.toggle('active', i <= idx);
            l.style.color = i <= idx ? '#D4A24E' : '#cdb8d6';
        });
    });
});
// Init warna bintang yang sudah dipilih
const checkedInit = document.querySelector('#starPicker input:checked');
if(checkedInit) {
    const idx = parseInt(checkedInit.value) - 1;
    labels.forEach((l, i) => l.style.color = i <= idx ? '#D4A24E' : '#cdb8d6');
}
</script>
@endpush