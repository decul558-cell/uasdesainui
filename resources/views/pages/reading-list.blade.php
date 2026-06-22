@extends('layouts.app')
@section('title', 'Daftar Baca')
@push('styles')
<style>
    .page-header{background: linear-gradient(135deg,#3D2645 0%,#5B4B8A 100%);padding:3rem 2rem;color:white;text-align:center;}
    .page-header-title{font-family:'Playfair Display',serif;font-size:clamp(2rem,4vw,3rem);font-weight:900;margin-bottom:0.5rem;}
    .reading-layout{padding:3rem 0;}
    .reading-tabs{display:flex;gap:1rem;margin-bottom:2rem;flex-wrap:wrap;}
    .reading-tab{display:flex;align-items:center;gap:0.5rem;padding:0.6rem 1.25rem;border-radius:50px;font-size:0.875rem;font-weight:700;cursor:pointer;transition:var(--transition);border:2px solid var(--cream-dark);background:white;color:var(--text-muted);font-family:'Plus Jakarta Sans',sans-serif;}
    .reading-tab.active{background:#3D2645;color:white;border-color:#3D2645;}
    .reading-tab .count{background:#8E3B5C;color:white;width:20px;height:20px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.7rem;}
    .reading-section{display:none;}
    .reading-section.active{display:block;}
    .reading-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1.5rem;margin-top:1.5rem;}
    .reading-card{background:white;border-radius:16px;overflow:hidden;box-shadow:var(--shadow);transition:var(--transition);position:relative;}
    .reading-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg);}
    .reading-cover{height:180px;display:flex;align-items:center;justify-content:center;font-size:2.5rem;position:relative;}
    .reading-cover img{width:100%;height:100%;object-fit:cover;}
    .reading-status-badge{position:absolute;top:10px;left:10px;padding:0.2rem 0.6rem;border-radius:50px;font-size:0.65rem;font-weight:700;text-transform:uppercase;}
    .badge-want{background:#EFF6FF;color:#1E40AF;}
    .badge-reading{background:#FEF3C7;color:#92400E;}
    .badge-finished{background:#ECFDF5;color:#065F46;}
    .reading-body{padding:1.1rem;}
    .reading-title{font-family:'Playfair Display',serif;font-size:0.95rem;font-weight:700;color:#3D2645;margin-bottom:0.25rem;line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
    .reading-author{font-size:0.75rem;color:var(--text-muted);margin-bottom:0.75rem;}
    .reading-actions{display:flex;gap:0.5rem;flex-wrap:wrap;}
    .btn-status{padding:0.3rem 0.65rem;border-radius:6px;font-size:0.72rem;font-weight:700;border:none;cursor:pointer;transition:var(--transition);font-family:'Plus Jakarta Sans',sans-serif;}
    .btn-reading{background:#FEF3C7;color:#92400E;}
    .btn-reading:hover{background:#F59E0B;color:white;}
    .btn-finished{background:#ECFDF5;color:#065F46;}
    .btn-finished:hover{background:#10B981;color:white;}
    .btn-remove{background:#FEF2F2;color:#ef4444;}
    .btn-remove:hover{background:#ef4444;color:white;}
    .empty-state{text-align:center;padding:4rem 2rem;background:white;border-radius:16px;box-shadow:var(--shadow);}
    .empty-state h3{color:#3D2645;}
    .btn-primary-maroon{background:#8E3B5C;color:white;display:inline-flex;align-items:center;gap:0.5rem;padding:0.6rem 1.4rem;border-radius:50px;font-weight:600;font-size:0.875rem;text-decoration:none;transition:var(--transition);}
    .btn-primary-maroon:hover{background:#3D2645;}
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="page-header-title">Daftar Baca</h1>
        <p style="opacity:0.75;font-size:0.95rem;">Kelola buku yang ingin, sedang, dan sudah kamu baca</p>
    </div>
</div>

<div class="container">
    <div class="reading-layout">
        <div class="reading-tabs">
            <button class="reading-tab active" onclick="showTab('want')">
                📚 Ingin Dibaca <span class="count">{{ $wantToRead->count() }}</span>
            </button>
            <button class="reading-tab" onclick="showTab('reading')">
                📖 Sedang Dibaca <span class="count">{{ $reading->count() }}</span>
            </button>
            <button class="reading-tab" onclick="showTab('finished')">
                ✅ Selesai Dibaca <span class="count">{{ $finished->count() }}</span>
            </button>
        </div>

        @php
            $sections = [
                'want'     => ['data' => $wantToRead, 'label' => 'Ingin Dibaca', 'badge' => 'badge-want'],
                'reading'  => ['data' => $reading,    'label' => 'Sedang Dibaca','badge' => 'badge-reading'],
                'finished' => ['data' => $finished,   'label' => 'Selesai',      'badge' => 'badge-finished'],
            ];
            $colors = ['#3D2645','#8E3B5C','#2D5E4A','#C9A84C'];
        @endphp

        @foreach($sections as $key => $section)
        <div class="reading-section {{ $key === 'want' ? 'active' : '' }}" id="section-{{ $key }}">
            @if($section['data']->count())
            <div class="reading-grid">
                @foreach($section['data'] as $i => $item)
                <div class="reading-card reveal" style="transition-delay:{{ $i * 0.05 }}s">
                    <div class="reading-cover" style="background:linear-gradient(135deg,{{ $colors[$i%4] }},#8E3B5C)">
                        @if($item->product->cover)
                            <img src="{{ Storage::url($item->product->cover) }}" alt="{{ $item->product->title }}">
                        @else
                            📖
                        @endif
                        <span class="reading-status-badge {{ $section['badge'] }}">{{ $section['label'] }}</span>
                    </div>
                    <div class="reading-body">
                        <a href="{{ route('products.show', $item->product->slug) }}" style="text-decoration:none;">
                            <div class="reading-title">{{ $item->product->title }}</div>
                        </a>
                        <div class="reading-author">{{ $item->product->author }}</div>
                        <div class="reading-actions">
                            @if($key !== 'reading')
                            <form method="POST" action="{{ route('reading-list.update', $item->id) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="reading">
                                <button type="submit" class="btn-status btn-reading">📖 Baca</button>
                            </form>
                            @endif
                            @if($key !== 'finished')
                            <form method="POST" action="{{ route('reading-list.update', $item->id) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="finished">
                                <button type="submit" class="btn-status btn-finished">✅ Selesai</button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('reading-list.destroy', $item->id) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-status btn-remove">🗑</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state reveal">
                <div style="font-size:4rem;margin-bottom:1rem;">📚</div>
                <h3 style="font-family:'Playfair Display',serif;margin-bottom:0.5rem;">Belum Ada Buku</h3>
                <p style="color:var(--text-muted);margin-bottom:1.5rem;">Tambahkan buku ke daftar {{ $section['label'] }}</p>
                <a href="{{ route('products.index') }}" class="btn-primary-maroon">Jelajahi Katalog</a>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showTab(tab) {
        document.querySelectorAll('.reading-section').forEach(s => s.classList.remove('active'));
        document.querySelectorAll('.reading-tab').forEach(t => t.classList.remove('active'));
        document.getElementById('section-' + tab).classList.add('active');
        event.target.closest('.reading-tab').classList.add('active');
    }
</script>
@endpush