@extends('layouts.app')
@section('title', $article->title)
@push('styles')
<style>
    .article-hero{background: linear-gradient(135deg,#3D2645 0%,#5B4B8A 100%);padding:4rem 2rem;color:white;text-align:center;position:relative;overflow:hidden;}
    .article-hero::before{content:'✍️';position:absolute;font-size:15rem;opacity:0.06;bottom:-3rem;right:-3rem;}
    .article-hero-category{display:inline-flex;align-items:center;gap:0.5rem;background:rgba(212,162,78,0.2);color:#D4A24E;padding:0.35rem 0.9rem;border-radius:50px;font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:1.5rem;}
    .article-hero-title{font-family:'Playfair Display',serif;font-size:clamp(1.8rem,4vw,2.75rem);font-weight:900;line-height:1.25;max-width:780px;margin:0 auto 1.5rem;}
    .article-hero-meta{display:flex;align-items:center;justify-content:center;gap:1.75rem;flex-wrap:wrap;opacity:0.8;font-size:0.85rem;position:relative;}
    .article-hero-meta span{display:flex;align-items:center;gap:0.4rem;}

    .article-layout{display:grid;grid-template-columns:1fr 300px;gap:3rem;padding:4rem 0;}
    .article-content{background:white;border-radius:20px;padding:3rem;box-shadow:var(--shadow);}
    .article-content p{color:var(--text);line-height:1.85;font-size:1.05rem;margin-bottom:1.5rem;}
    .article-content h2{font-family:'Playfair Display',serif;font-size:1.55rem;font-weight:700;color:var(--plum);margin:2.25rem 0 1rem;line-height:1.3;}
    .article-content h3{font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--plum);margin:1.75rem 0 0.75rem;line-height:1.3;}
    .article-content ul,.article-content ol{margin:1rem 0 1.5rem 1.5rem;color:var(--text);line-height:1.85;font-size:1.05rem;}
    .article-content li{margin-bottom:0.5rem;}
    .article-content a{color:var(--magenta);text-decoration:underline;text-underline-offset:2px;}
    .article-content img{max-width:100%;border-radius:12px;margin:1.5rem 0;}
    .article-content blockquote{border-left:4px solid var(--magenta);padding:1rem 1.5rem;background:var(--mist);border-radius:0 12px 12px 0;margin:2rem 0;font-style:italic;color:var(--indigo);font-size:1.05rem;line-height:1.8;}
    .article-content strong{color:var(--plum);font-weight:700;}

    .article-share{display:flex;align-items:center;gap:1rem;margin-top:3rem;padding-top:2rem;border-top:2px solid var(--mist-dark);flex-wrap:wrap;}
    .share-label{font-weight:700;color:var(--plum);font-size:0.9rem;}
    .share-btn{display:inline-flex;align-items:center;gap:0.5rem;padding:0.5rem 1.25rem;border-radius:50px;font-size:0.8rem;font-weight:700;text-decoration:none;transition:var(--transition);}
    .share-btn:hover{transform:translateY(-2px);}

    .sidebar-widget{background:white;border-radius:16px;padding:1.5rem;box-shadow:var(--shadow);margin-bottom:1.5rem;}
    .widget-title{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:var(--plum);margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:2px solid var(--mist-dark);}
    .related-article{display:flex;gap:1rem;padding:0.75rem 0;border-bottom:1px solid var(--mist-dark);text-decoration:none;color:inherit;transition:var(--transition);}
    .related-article:last-child{border-bottom:none;}
    .related-article:hover{color:var(--magenta);}
    .related-article-thumb{width:60px;height:60px;border-radius:8px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:white;}
    .related-article-title{font-size:0.85rem;font-weight:600;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
    .related-article-date{font-size:0.75rem;color:var(--text-muted);margin-top:0.25rem;}
    .author-card{display:flex;align-items:center;gap:1rem;}
    .author-avatar{width:50px;height:50px;border-radius:50%;background:linear-gradient(135deg,var(--plum),var(--magenta));display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:1.1rem;flex-shrink:0;}
    .author-name{font-weight:700;color:var(--plum);font-size:0.95rem;}
    .author-role{font-size:0.8rem;color:var(--text-muted);}
    @media(max-width:768px){
        .article-layout{grid-template-columns:1fr;}
        .article-content{padding:1.5rem;}
    }
</style>
@endpush

@section('content')
<div class="article-hero">
    <div class="container">
        <div class="article-hero-category"><i class="fas fa-book-open"></i> Artikel</div>
        <h1 class="article-hero-title">{{ $article->title }}</h1>
        <div class="article-hero-meta">
            <span><i class="fas fa-user"></i> {{ $article->user->name }}</span>
            <span><i class="fas fa-calendar-alt"></i> {{ $article->published_at?->format('d F Y') }}</span>
            <span><i class="fas fa-clock"></i> {{ ceil(str_word_count(strip_tags($article->body)) / 200) }} menit baca</span>
        </div>
    </div>
</div>

<div class="container">
    <div class="article-layout">
        <!-- CONTENT -->
        <div>
            <div class="article-content reveal">
                {!! $article->body !!}

                <div class="article-share">
                    <span class="share-label">Bagikan:</span>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}" target="_blank" class="share-btn" style="background:#1DA1F2;color:white;">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . url()->current()) }}" target="_blank" class="share-btn" style="background:#25D366;color:white;">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="share-btn" style="background:#1877F2;color:white;">
                        <i class="fab fa-facebook"></i> Facebook
                    </a>
                </div>
            </div>

            <!-- BACK -->
            <div style="margin-top:2rem;">
                <a href="{{ route('articles.index') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Kembali ke Artikel
                </a>
            </div>
        </div>

        <!-- SIDEBAR -->
        <aside>
            <!-- AUTHOR -->
            <div class="sidebar-widget reveal">
                <div class="widget-title">Penulis</div>
                <div class="author-card">
                    <div class="author-avatar">{{ strtoupper(substr($article->user->name, 0, 1)) }}</div>
                    <div>
                        <div class="author-name">{{ $article->user->name }}</div>
                        <div class="author-role">Kontributor Pustaka Nusantara</div>
                    </div>
                </div>
            </div>

            <!-- RELATED -->
            @if($related->count())
            <div class="sidebar-widget reveal">
                <div class="widget-title">Artikel Terkait</div>
                @php $bgs=['linear-gradient(135deg,#3D2645,#8E3B5C)','linear-gradient(135deg,#5B4B8A,#3D2645)','linear-gradient(135deg,#8E3B5C,#D4A24E)']; @endphp
                @foreach($related as $i => $rel)
                <a href="{{ route('articles.show', $rel->slug) }}" class="related-article">
                    <div class="related-article-thumb" style="background:{{ $bgs[$i%3] }}">📖</div>
                    <div>
                        <div class="related-article-title">{{ $rel->title }}</div>
                        <div class="related-article-date">{{ $rel->published_at?->format('d M Y') }}</div>
                    </div>
                </a>
                @endforeach
            </div>
            @endif

            <!-- CTA -->
            <div class="sidebar-widget reveal" style="background:linear-gradient(135deg,var(--plum),var(--magenta));color:white;text-align:center;">
                <div style="font-size:2.5rem;margin-bottom:1rem;">📚</div>
                <div style="font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;margin-bottom:0.75rem;">Temukan Buku Impianmu</div>
                <p style="font-size:0.85rem;opacity:0.85;margin-bottom:1.25rem;line-height:1.6;">Jelajahi ribuan koleksi buku pilihan kami.</p>
                <a href="{{ route('products.index') }}" class="btn" style="background:white;color:var(--plum);width:100%;justify-content:center;">
                    <i class="fas fa-book-open"></i> Lihat Katalog
                </a>
            </div>
        </aside>
    </div>
</div>
@endsection
