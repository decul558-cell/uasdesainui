@extends('layouts.app')
@section('title', 'Paket Bundle')
@push('styles')
<style>
    .bl-hero{background:linear-gradient(135deg,var(--brown) 0%,var(--brown-mid) 60%,var(--orange) 100%);padding:4rem 0 3rem;position:relative;overflow:hidden;text-align:center;color:white;}
    .bl-hero::before{content:'📦';position:absolute;font-size:14rem;opacity:0.06;top:-2rem;left:-2rem;}
    .bl-hero::after{content:'🎁';position:absolute;font-size:12rem;opacity:0.06;bottom:-3rem;right:-2rem;}
    .bl-hero-badge{display:inline-flex;align-items:center;gap:0.5rem;background:rgba(255,255,255,0.15);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,0.2);border-radius:50px;padding:0.5rem 1.1rem;font-size:0.8rem;font-weight:700;margin-bottom:1.25rem;position:relative;z-index:2;}
    .bl-hero-title{font-family:'Playfair Display',serif;font-size:clamp(2rem,5vw,3rem);font-weight:900;margin-bottom:0.75rem;position:relative;z-index:2;}
    .bl-hero-sub{color:rgba(255,255,255,0.8);font-size:0.95rem;max-width:560px;margin:0 auto;position:relative;z-index:2;}

    .bl-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.75rem;margin-top:-2rem;position:relative;z-index:5;}
    .bl-card{background:white;border-radius:18px;overflow:hidden;box-shadow:var(--shadow);transition:var(--transition);text-decoration:none;color:inherit;display:flex;flex-direction:column;}
    .bl-card:hover{transform:translateY(-6px);box-shadow:var(--shadow-lg);}
    .bl-cover{height:200px;position:relative;overflow:hidden;}
    .bl-cover img{width:100%;height:100%;object-fit:cover;}
    .bl-cover-placeholder{width:100%;height:100%;background:linear-gradient(135deg,var(--brown),var(--orange));display:flex;align-items:center;justify-content:center;font-size:3.5rem;}
    .bl-discount-badge{position:absolute;top:12px;right:12px;background:#ef4444;color:white;font-size:0.72rem;font-weight:800;padding:0.3rem 0.7rem;border-radius:50px;}
    .bl-count-badge{position:absolute;bottom:12px;left:12px;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);color:white;font-size:0.74rem;font-weight:700;padding:0.3rem 0.7rem;border-radius:50px;}
    .bl-body{padding:1.25rem;flex:1;display:flex;flex-direction:column;}
    .bl-title{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:700;color:var(--brown);margin-bottom:0.5rem;line-height:1.35;}
    .bl-desc{font-size:0.8rem;color:var(--text-muted);line-height:1.6;margin-bottom:0.9rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;flex:1;}
    .bl-books-preview{display:flex;gap:0.3rem;margin-bottom:1rem;}
    .bl-books-preview span{background:var(--cream);color:var(--brown-mid);font-size:0.68rem;font-weight:600;padding:0.2rem 0.55rem;border-radius:50px;}
    .bl-footer{display:flex;align-items:center;justify-content:space-between;padding-top:0.9rem;border-top:1px solid var(--cream-dark);}
    .bl-price-old{font-size:0.75rem;color:var(--text-muted);text-decoration:line-through;}
    .bl-price-new{font-weight:800;color:var(--orange);font-size:1.05rem;}
    .bl-arrow{background:var(--brown);color:white;width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.85rem;flex-shrink:0;transition:var(--transition);}
    .bl-card:hover .bl-arrow{background:var(--orange);}

    .bl-empty{text-align:center;padding:5rem 2rem;color:var(--text-muted);}
    .bl-empty i{font-size:3.5rem;margin-bottom:1rem;opacity:0.3;}

    @media(max-width:768px){
        .bl-hero{padding:3rem 0 2.5rem;}
        .bl-grid{grid-template-columns:repeat(2,1fr);gap:1rem;}
    }
</style>
@endpush

@section('content')

<!-- HERO -->
<section class="bl-hero">
    <div class="container">
        <div class="bl-hero-badge"><i class="fas fa-gift"></i> Hemat Lebih Banyak</div>
        <h1 class="bl-hero-title">Paket Bundle Pilihan</h1>
        <p class="bl-hero-sub">Beberapa buku favorit dikemas jadi satu paket dengan harga lebih hemat dibanding beli satuan.</p>
    </div>
</section>

<div class="container">
    @if($bundles->isEmpty())
        <div class="bl-empty reveal">
            <i class="fas fa-box-open"></i>
            <p style="font-size:1.1rem;font-weight:600;margin-bottom:0.5rem;">Belum ada bundle tersedia</p>
            <p style="font-size:0.875rem;">Pantau terus, paket hemat akan segera hadir!</p>
        </div>
    @else
        <div class="bl-grid">
            @foreach($bundles as $i => $bundle)
            @php
                $savings = max($bundle->original_price - $bundle->bundle_price, 0);
                $discountPct = $bundle->original_price > 0 ? round(($savings / $bundle->original_price) * 100) : 0;
            @endphp
            <a href="{{ route('bundles.show', $bundle->slug) }}" class="bl-card reveal" style="transition-delay:{{ ($i % 8) * 0.05 }}s">
                <div class="bl-cover">
                    @if($bundle->image)
                        <img src="{{ Storage::url($bundle->image) }}" alt="{{ $bundle->name }}">
                    @else
                        <div class="bl-cover-placeholder">📦</div>
                    @endif

                    @if($discountPct > 0)
                        <div class="bl-discount-badge">Hemat {{ $discountPct }}%</div>
                    @endif

                    <div class="bl-count-badge"><i class="fas fa-book"></i> {{ $bundle->items->count() }} Buku</div>
                </div>
                <div class="bl-body">
                    <h3 class="bl-title">{{ $bundle->name }}</h3>
                    @if($bundle->description)
                        <p class="bl-desc">{{ $bundle->description }}</p>
                    @endif

                    <div class="bl-books-preview">
                        @foreach($bundle->items->take(3) as $item)
                            <span>{{ \Illuminate\Support\Str::limit($item->product->title ?? '-', 14) }}</span>
                        @endforeach
                        @if($bundle->items->count() > 3)
                            <span>+{{ $bundle->items->count() - 3 }}</span>
                        @endif
                    </div>

                    <div class="bl-footer">
                        <div>
                            @if($bundle->original_price > $bundle->bundle_price)
                                <div class="bl-price-old">Rp {{ number_format($bundle->original_price, 0, ',', '.') }}</div>
                            @endif
                            <div class="bl-price-new">Rp {{ number_format($bundle->bundle_price, 0, ',', '.') }}</div>
                        </div>
                        <div class="bl-arrow"><i class="fas fa-arrow-right"></i></div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(el => { if(el.isIntersecting) el.target.classList.add('visible'); });
    }, {threshold:0.1});
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>
@endpush