@extends('layouts.app')
@section('title', 'Artikel')
@push('styles')
<style>
    .page-header{background:linear-gradient(135deg,#3D2645 0%,#5B4B8A 100%);padding:4rem 2rem;color:white;text-align:center;position:relative;overflow:hidden;}
    .page-header::before{content:'';position:absolute;top:-50%;right:-10%;width:300px;height:300px;background:rgba(142,59,92,0.2);border-radius:50%;}
    .page-header::after{content:'';position:absolute;bottom:-30%;left:-5%;width:200px;height:200px;background:rgba(91,75,138,0.3);border-radius:50%;}
    .page-header-title{font-family:'Playfair Display',serif;font-size:clamp(2rem,4vw,3rem);font-weight:900;margin-bottom:0.5rem;position:relative;z-index:1;}
    .page-header-sub{opacity:0.8;font-size:0.95rem;position:relative;z-index:1;}
    .articles-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:2rem;margin-top:3rem;}
    .article-card{background:white;border-radius:20px;overflow:hidden;box-shadow:var(--shadow);transition:var(--transition);text-decoration:none;color:inherit;display:flex;flex-direction:column;border:1px solid var(--cream-dark);}
    .article-card:hover{transform:translateY(-6px);box-shadow:var(--shadow-lg);}
    .article-thumb{height:200px;display:flex;align-items:center;justify-content:center;font-size:4rem;position:relative;overflow:hidden;}
    .article-thumb-label{position:absolute;top:12px;left:12px;background:rgba(61,38,69,0.7);color:white;font-size:0.7rem;font-weight:700;padding:0.25rem 0.6rem;border-radius:50px;backdrop-filter:blur(4px);}
    .article-body{padding:1.5rem;flex:1;display:flex;flex-direction:column;}
    .article-meta{display:flex;align-items:center;gap:1rem;margin-bottom:0.75rem;}
    .article-date{font-size:0.75rem;color:var(--text-muted);font-weight:600;}
    .article-author{font-size:0.75rem;color:var(--text-muted);}
    .article-author span{color:var(--magenta);font-weight:600;}
    .article-title{font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--plum);line-height:1.4;margin-bottom:0.75rem;}
    .article-excerpt{font-size:0.875rem;color:var(--text-muted);line-height:1.7;flex:1;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;}
    .article-footer{margin-top:1.25rem;display:flex;align-items:center;justify-content:space-between;}
    .read-more{color:var(--magenta);font-weight:700;font-size:0.875rem;display:flex;align-items:center;gap:0.4rem;transition:var(--transition);}
    .read-more i{transition:var(--transition);}
    .article-card:hover .read-more i{transform:translateX(4px);}
    .pagination-wrap{margin-top:3rem;display:flex;justify-content:center;}
    .empty-state{text-align:center;padding:5rem 2rem;}
    @media(max-width:768px){.articles-grid{grid-template-columns:1fr;}}
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="page-header-title">Artikel & Blog</h1>
        <p class="page-header-sub">Inspirasi, rekomendasi, dan tips seputar dunia buku</p>
    </div>
</div>

<div class="container section">
    @if($articles->count())
    @php
        $bgs = [
            'linear-gradient(135deg,#3D2645,#8E3B5C)',
            'linear-gradient(135deg,#5B4B8A,#3D2645)',
            'linear-gradient(135deg,#8E3B5C,#D4A24E)',
            'linear-gradient(135deg,#2C1B33,#5B4B8A)',
            'linear-gradient(135deg,#D4A24E,#8E3B5C)',
        ];
        $icons = ['📚','✍️','💡','📖','🌟'];
    @endphp
    <div class="articles-grid">
        @foreach($articles as $i => $article)
        <a href="{{ route('articles.show', $article->slug) }}" class="article-card reveal" style="transition-delay:{{ ($i%6) * 0.08 }}s">
            <div class="article-thumb" style="background:{{ $bgs[$i%5] }}">
                {{ $icons[$i%5] }}
                <span class="article-thumb-label">Artikel</span>
            </div>
            <div class="article-body">
                <div class="article-meta">
                    <span class="article-date"><i class="fas fa-calendar-alt"></i> {{ $article->published_at?->format('d M Y') }}</span>
                    <span class="article-author">oleh <span>{{ $article->user->name }}</span></span>
                </div>
                <h2 class="article-title">{{ $article->title }}</h2>
                <p class="article-excerpt">{{ $article->excerpt }}</p>
                <div class="article-footer">
                    <span class="read-more">Baca Selengkapnya <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    <div class="pagination-wrap">{{ $articles->links() }}</div>
    @else
    <div class="empty-state">
        <div style="font-size:4rem;margin-bottom:1rem;">📭</div>
        <h3 style="font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--plum);margin-bottom:0.5rem;">Belum Ada Artikel</h3>
        <p style="color:var(--text-muted);">Artikel akan segera hadir. Pantau terus ya!</p>
    </div>
    @endif
</div>
@endsection