@extends('layouts.app')
@section('title', 'Pre-Order Saya')
@push('styles')
<style>
    .page-header{background:linear-gradient(135deg,#3D2645 0%,#5B4B8A 100%);padding:4rem 2rem;color:white;text-align:center;position:relative;overflow:hidden;}
    .page-header-title{font-family:'Playfair Display',serif;font-size:clamp(2rem,4vw,3rem);font-weight:900;margin-bottom:0.5rem;}
    .page-header-sub{opacity:0.85;font-size:0.95rem;}
    .preorder-list{display:flex;flex-direction:column;gap:1.25rem;padding:3rem 0;}
    .preorder-card{background:white;border-radius:16px;padding:1.5rem;box-shadow:var(--shadow);display:flex;gap:1.5rem;align-items:center;transition:var(--transition);}
    .preorder-card:hover{box-shadow:var(--shadow-lg);}
    .preorder-cover{width:70px;height:90px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:1.8rem;overflow:hidden;}
    .preorder-cover img{width:100%;height:100%;object-fit:cover;}
    .preorder-info{flex:1;min-width:0;}
    .preorder-category{font-size:0.7rem;font-weight:700;color:var(--magenta);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.25rem;}
    .preorder-title{font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;color:var(--plum);margin-bottom:0.25rem;line-height:1.3;}
    .preorder-author{font-size:0.8rem;color:var(--text-muted);margin-bottom:0.5rem;}
    .preorder-meta{display:flex;gap:1.25rem;font-size:0.78rem;color:var(--text-muted);}
    .preorder-meta strong{color:var(--plum);}
    .preorder-note{font-size:0.78rem;color:var(--text-muted);font-style:italic;margin-top:0.4rem;}
    .preorder-actions{display:flex;flex-direction:column;align-items:flex-end;gap:0.6rem;flex-shrink:0;}
    .status-badge{display:inline-flex;align-items:center;gap:0.35rem;padding:0.3rem 0.8rem;border-radius:50px;font-size:0.72rem;font-weight:700;}
    .status-waiting{background:#f5e8c9;color:#8a6418;}
    .status-ready{background:#d6ecdf;color:#1d6b3f;}
    .status-cancelled{background:#f3d8d8;color:#8a2c2c;}
    .btn-cancel-preorder{background:none;border:1.5px solid #f3d8d8;color:#a83333;font-size:0.78rem;font-weight:700;padding:0.4rem 0.9rem;border-radius:8px;cursor:pointer;transition:var(--transition);font-family:'Plus Jakarta Sans',sans-serif;}
    .btn-cancel-preorder:hover{background:#a83333;color:white;border-color:#a83333;}
    .empty-state{text-align:center;padding:5rem 2rem;background:white;border-radius:20px;box-shadow:var(--shadow);}
    .empty-state-icon{font-size:5rem;margin-bottom:1rem;}
    @media(max-width:768px){
        .preorder-card{flex-wrap:wrap;}
        .preorder-actions{flex-direction:row;width:100%;justify-content:space-between;align-items:center;}
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="page-header-title">Pre-Order Saya</h1>
        <p class="page-header-sub">Buku yang sedang menunggu restock</p>
    </div>
</div>

<div class="container">
    @if($preorders->count())
    <div class="preorder-list">
        @php
            $colors = ['#3D2645','#5B4B8A','#8E3B5C','#D4A24E'];
            $statusLabel = ['waiting' => '⏳ Menunggu Stok', 'ready' => '✅ Stok Tersedia', 'cancelled' => '❌ Dibatalkan'];
            $statusClass = ['waiting' => 'status-waiting', 'ready' => 'status-ready', 'cancelled' => 'status-cancelled'];
        @endphp
        @foreach($preorders as $i => $preorder)
        <div class="preorder-card reveal" style="transition-delay:{{ $i * 0.05 }}s">
            <div class="preorder-cover" style="background:linear-gradient(135deg,{{ $colors[$i%4] }},var(--magenta));">
                @if($preorder->product->cover)
                    <img src="{{ Storage::url($preorder->product->cover) }}" alt="{{ $preorder->product->title }}">
                @else
                    📖
                @endif
            </div>
            <div class="preorder-info">
                <div class="preorder-category">{{ $preorder->product->category->name ?? '-' }}</div>
                <a href="{{ route('products.show', $preorder->product->slug) }}" style="text-decoration:none;">
                    <div class="preorder-title">{{ $preorder->product->title }}</div>
                </a>
                <div class="preorder-author">{{ $preorder->product->author }}</div>
                <div class="preorder-meta">
                    <span>Jumlah: <strong>{{ $preorder->quantity }}</strong></span>
                    <span>Didaftarkan: <strong>{{ $preorder->created_at->format('d M Y') }}</strong></span>
                </div>
                @if($preorder->note)
                <div class="preorder-note">📝 "{{ $preorder->note }}"</div>
                @endif
            </div>
            <div class="preorder-actions">
                <span class="status-badge {{ $statusClass[$preorder->status] ?? '' }}">
                    {{ $statusLabel[$preorder->status] ?? $preorder->status }}
                </span>
                @if($preorder->status === 'waiting')
                <form method="POST" action="{{ route('preorders.destroy', $preorder->id) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-cancel-preorder" onclick="return confirm('Batalkan pre-order ini?')">
                        <i class="fas fa-times"></i> Batalkan
                    </button>
                </form>
                @elseif($preorder->status === 'ready')
                <a href="{{ route('products.show', $preorder->product->slug) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-shopping-cart"></i> Beli Sekarang
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div style="padding:3rem 0;">
        <div class="empty-state reveal">
            <div class="empty-state-icon">⏳</div>
            <h2 style="font-family:'Playfair Display',serif;font-size:1.8rem;color:var(--plum);margin-bottom:0.75rem;">Belum Ada Pre-Order</h2>
            <p style="color:var(--text-muted);margin-bottom:2rem;">Pre-order buku yang stoknya sedang habis, kami akan beri tahu saat sudah tersedia!</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-book-open"></i> Jelajahi Katalog
            </a>
        </div>
    </div>
    @endif
</div>
@endsection