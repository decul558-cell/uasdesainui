@extends('layouts.admin')

@section('title', 'Abandoned Cart Tracker')

@push('styles')
<style>
    .stat-mini { background: white; border-radius: 12px; padding: 1.25rem 1.5rem; box-shadow: var(--shadow); display: flex; align-items: center; gap: 1rem; }
    .stat-mini-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0; }
    .stat-mini-val { font-size: 1.5rem; font-weight: 800; font-family: 'Playfair Display', serif; color: var(--brown); line-height: 1; }
    .stat-mini-label { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.2rem; }
    .user-abandon-card { background: white; border-radius: 14px; box-shadow: var(--shadow); margin-bottom: 1rem; overflow: hidden; transition: var(--transition); }
    .user-abandon-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-1px); }
    .user-abandon-header { display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem; border-bottom: 1px solid var(--cream-dark); cursor: pointer; }
    .user-info { display: flex; align-items: center; gap: 0.75rem; }
    .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--brown), var(--orange)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1rem; flex-shrink: 0; }
    .user-name { font-weight: 700; font-size: 0.9rem; color: var(--brown); }
    .user-email { font-size: 0.75rem; color: var(--text-muted); }
    .abandon-meta { display: flex; align-items: center; gap: 1rem; }
    .abandon-badge { background: #FEF3C7; color: #92400E; font-size: 0.7rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 50px; }
    .abandon-value { font-weight: 800; color: var(--orange); font-size: 0.95rem; }
    .abandon-time { font-size: 0.75rem; color: var(--text-muted); }
    .user-abandon-items { padding: 1rem 1.25rem; display: none; }
    .user-abandon-items.open { display: block; }
    .cart-item-row { display: flex; align-items: center; gap: 0.75rem; padding: 0.6rem 0; border-bottom: 1px solid var(--cream-dark); }
    .cart-item-row:last-child { border-bottom: none; }
    .cart-item-thumb { width: 44px; height: 56px; object-fit: cover; border-radius: 6px; background: var(--cream-dark); flex-shrink: 0; }
    .cart-item-thumb-placeholder { width: 44px; height: 56px; border-radius: 6px; background: var(--cream-dark); display: flex; align-items: center; justify-content: center; color: var(--text-muted); flex-shrink: 0; }
    .cart-item-title { font-weight: 600; font-size: 0.85rem; color: var(--brown); }
    .cart-item-meta { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.15rem; }
    .cart-item-price { margin-left: auto; font-weight: 700; font-size: 0.85rem; color: var(--orange); white-space: nowrap; }
    .filter-bar { display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; margin-bottom: 1.5rem; }
    .filter-chip { padding: 0.4rem 1rem; border-radius: 50px; font-size: 0.8rem; font-weight: 600; border: 1.5px solid var(--cream-dark); background: white; color: var(--text-muted); cursor: pointer; text-decoration: none; transition: var(--transition); }
    .filter-chip:hover, .filter-chip.active { background: var(--brown); color: white; border-color: var(--brown); }
    .empty-state { text-align: center; padding: 4rem 2rem; color: var(--text-muted); }
    .empty-state i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.3; }
    .chevron { transition: transform 0.3s ease; }
    .chevron.open { transform: rotate(180deg); }
    .dismiss-form { display: inline; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1 class="page-title">🛒 Abandoned Cart Tracker</h1>
            <p class="page-sub">Pantau user yang menambahkan produk ke keranjang tapi belum checkout</p>
        </div>
    </div>
</div>

{{-- Stats --}}
<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1rem; margin-bottom:2rem;">
    <div class="stat-mini reveal">
        <div class="stat-mini-icon" style="background:#FEF3C7;">🛒</div>
        <div>
            <div class="stat-mini-val">{{ $stats['total_users'] }}</div>
            <div class="stat-mini-label">User dengan cart abandon</div>
        </div>
    </div>
    <div class="stat-mini reveal">
        <div class="stat-mini-icon" style="background:#FEE2E2;">📦</div>
        <div>
            <div class="stat-mini-val">{{ $stats['total_items'] }}</div>
            <div class="stat-mini-label">Total item ditinggalkan</div>
        </div>
    </div>
    <div class="stat-mini reveal">
        <div class="stat-mini-icon" style="background:#ECFDF5;">💰</div>
        <div>
            <div class="stat-mini-val">Rp {{ number_format($stats['estimated_loss'], 0, ',', '.') }}</div>
            <div class="stat-mini-label">Estimasi nilai hilang</div>
        </div>
    </div>
    <div class="stat-mini reveal">
        <div class="stat-mini-icon" style="background:#EFF6FF;">📊</div>
        <div>
            <div class="stat-mini-val">{{ $stats['avg_items'] }}</div>
            <div class="stat-mini-label">Rata-rata item per user</div>
        </div>
    </div>
</div>

{{-- Filter Bar --}}
<div class="filter-bar">
    <form method="GET" style="display:flex; gap:0.75rem; align-items:center; flex-wrap:wrap; width:100%;">
        {{-- Search --}}
        <div style="position:relative; flex:1; min-width:200px;">
            <i class="fas fa-search" style="position:absolute; left:0.9rem; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:0.8rem;"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email user..." class="form-control" style="padding-left:2.5rem; height:38px; font-size:0.85rem;">
        </div>
        {{-- Range filter --}}
        <a href="{{ request()->fullUrlWithQuery(['range' => '']) }}" class="filter-chip {{ !request('range') ? 'active' : '' }}">Semua</a>
        <a href="{{ request()->fullUrlWithQuery(['range' => '2h']) }}" class="filter-chip {{ request('range') == '2h' ? 'active' : '' }}">2 Jam+</a>
        <a href="{{ request()->fullUrlWithQuery(['range' => '6h']) }}" class="filter-chip {{ request('range') == '6h' ? 'active' : '' }}">6 Jam+</a>
        <a href="{{ request()->fullUrlWithQuery(['range' => '24h']) }}" class="filter-chip {{ request('range') == '24h' ? 'active' : '' }}">24 Jam+</a>
        <a href="{{ request()->fullUrlWithQuery(['range' => '7d']) }}" class="filter-chip {{ request('range') == '7d' ? 'active' : '' }}">7 Hari+</a>
        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Cari</button>
    </form>
</div>

{{-- List --}}
@if($abandonedGroups->isEmpty())
    <div class="empty-state">
        <i class="fas fa-shopping-cart"></i>
        <p style="font-size:1.1rem; font-weight:600; margin-bottom:0.5rem;">Tidak ada abandoned cart</p>
        <p style="font-size:0.875rem;">Semua user sudah checkout atau belum ada yang menambahkan ke keranjang.</p>
    </div>
@else
    @foreach($abandonedGroups as $group)
        @php
            $items = $cartItems[$group->user_id] ?? collect();
            $user  = $items->first()?->user ?? \App\Models\User::find($group->user_id);
            $lastSeen = $group->last_seen ? \Carbon\Carbon::parse($group->last_seen) : null;
        @endphp
        <div class="user-abandon-card reveal">
            <div class="user-abandon-header" onclick="toggleItems({{ $group->user_id }})">
                <div class="user-info">
                    <div class="user-avatar">{{ strtoupper(substr($user?->name ?? '?', 0, 1)) }}</div>
                    <div>
                        <div class="user-name">{{ $user?->name ?? 'User #'.$group->user_id }}</div>
                        <div class="user-email">{{ $user?->email ?? '-' }}</div>
                    </div>
                </div>
                <div class="abandon-meta">
                    <span class="abandon-badge">{{ $group->item_count }} item</span>
                    @if($group->estimated_value > 0)
                        <span class="abandon-value">Rp {{ number_format($group->estimated_value, 0, ',', '.') }}</span>
                    @endif
                    @if($lastSeen)
                        <span class="abandon-time"><i class="fas fa-clock"></i> {{ $lastSeen->diffForHumans() }}</span>
                    @endif
                    {{-- Dismiss button --}}
                    <form class="dismiss-form" method="POST" action="{{ route('admin.abandoned-carts.dismiss', $group->user_id) }}"
                          onsubmit="return confirm('Hapus semua cart user ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-del btn-sm" title="Hapus / Dismiss" onclick="event.stopPropagation()">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    <i class="fas fa-chevron-down chevron" id="chevron-{{ $group->user_id }}"></i>
                </div>
            </div>
            {{-- Item detail (collapsed by default) --}}
            <div class="user-abandon-items" id="items-{{ $group->user_id }}">
                @forelse($items as $item)
                    <div class="cart-item-row">
                        @if($item->product?->cover_image)
                            <img src="{{ asset('storage/'.$item->product->cover_image) }}" class="cart-item-thumb" alt="">
                        @else
                            <div class="cart-item-thumb-placeholder"><i class="fas fa-book"></i></div>
                        @endif
                        <div style="flex:1; min-width:0;">
                            <div class="cart-item-title">
                                @if($item->bundle)
                                    <i class="fas fa-box-open" style="color:var(--orange);font-size:0.75rem;"></i>
                                    {{ $item->bundle->name }}
                                @else
                                    {{ $item->product?->title ?? 'Produk dihapus' }}
                                @endif
                            </div>
                            <div class="cart-item-meta">
                                Qty: {{ $item->quantity }}
                                @if($item->product?->author) · {{ $item->product->author }}@endif
                            </div>
                        </div>
                        @if($item->price)
                            <div class="cart-item-price">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                        @elseif($item->product?->price)
                            <div class="cart-item-price">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</div>
                        @endif
                    </div>
                @empty
                    <p style="color:var(--text-muted); font-size:0.85rem; text-align:center; padding:1rem;">Data item tidak tersedia.</p>
                @endforelse
            </div>
        </div>
    @endforeach

    {{-- Pagination --}}
    <div style="margin-top:1.5rem;">
        {{ $abandonedGroups->links() }}
    </div>
@endif
@endsection

@push('scripts')
<script>
function toggleItems(userId) {
    const el = document.getElementById('items-' + userId);
    const ch = document.getElementById('chevron-' + userId);
    el.classList.toggle('open');
    ch.classList.toggle('open');
}
</script>
@endpush