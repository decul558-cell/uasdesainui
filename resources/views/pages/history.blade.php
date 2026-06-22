@extends('layouts.app')
@section('title', 'Riwayat Pesanan')
@push('styles')
<style>
    .page-header{background: linear-gradient(135deg,#3D2645 0%,#5B4B8A 100%);padding:3rem 2rem;color:white;text-align:center;}
    .page-header-title{font-family:'Playfair Display',serif;font-size:clamp(2rem,4vw,3rem);font-weight:900;margin-bottom:0.5rem;}
    .page-header-sub{opacity:0.8;font-size:0.95rem;}
    .orders-list{display:flex;flex-direction:column;gap:1.5rem;padding:3rem 0;}
    .order-card{background:white;border-radius:20px;box-shadow:var(--shadow);overflow:hidden;transition:var(--transition);}
    .order-card:hover{box-shadow:var(--shadow-lg);}
    .order-header{display:flex;align-items:center;justify-content:space-between;padding:1.25rem 1.5rem;border-bottom:1px solid var(--mist-dark);flex-wrap:wrap;gap:1rem;}
    .order-code{font-weight:800;color:var(--plum);font-size:0.95rem;font-family:'Playfair Display',serif;}
    .order-date{font-size:0.8rem;color:var(--text-muted);}
    .status-badge{display:inline-flex;align-items:center;gap:0.4rem;padding:0.35rem 0.9rem;border-radius:50px;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.03em;}
    .status-pending{background:#f5e8c9;color:#8a6418;}
    .status-paid{background:#d6ecdf;color:#1d6b3f;}
    .status-shipped{background:#e3ddf0;color:#4a3a7a;}
    .status-delivered{background:#d6ecdf;color:#1d6b3f;}
    .status-cancelled{background:#f3d8d8;color:#8a2c2c;}
    .order-body{padding:1.5rem;}
    .order-items-list{display:flex;flex-direction:column;gap:0.75rem;margin-bottom:1.5rem;}
    .order-item{display:flex;align-items:center;gap:1rem;}
    .order-item-cover{width:50px;height:65px;border-radius:8px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:1.5rem;}
    .order-item-title{font-weight:700;color:var(--plum);font-size:0.875rem;flex:1;}
    .order-item-qty{font-size:0.8rem;color:var(--text-muted);}
    .order-item-price{font-weight:700;color:var(--magenta);font-size:0.9rem;}
    .order-footer{display:flex;align-items:center;justify-content:space-between;padding:1.25rem 1.5rem;background:var(--mist);border-top:1px solid var(--mist-dark);flex-wrap:wrap;gap:1rem;}
    .order-total{font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:900;color:var(--magenta);}
    .order-total-label{font-size:0.8rem;color:var(--text-muted);margin-bottom:0.15rem;}
    .order-info{display:flex;gap:2rem;flex-wrap:wrap;}
    .order-info-item{font-size:0.8rem;color:var(--text-muted);display:flex;align-items:center;gap:0.4rem;}
    .order-info-item strong{color:var(--plum);}
    .empty-state{text-align:center;padding:5rem 2rem;background:white;border-radius:20px;box-shadow:var(--shadow);}
    .empty-state-icon{font-size:5rem;margin-bottom:1rem;}

    /* ── TRACKING TIMELINE ── */
    .tracking-wrap{margin-top:1.25rem;padding-top:1.25rem;border-top:1px dashed var(--mist-dark);}
    .tracking-title{font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--text-muted);margin-bottom:1.25rem;}
    .tracking-steps{display:flex;align-items:flex-start;gap:0;}
    .tracking-step{flex:1;display:flex;flex-direction:column;align-items:center;position:relative;}
    .tracking-step:not(:last-child)::after{content:'';position:absolute;top:18px;left:calc(50% + 18px);right:calc(-50% + 18px);height:3px;background:var(--mist-dark);z-index:0;}
    .tracking-step.done:not(:last-child)::after{background:linear-gradient(90deg,var(--gold),#e0b264);}
    .tracking-step.active:not(:last-child)::after{background:linear-gradient(90deg,var(--gold),var(--mist-dark));}
    .tracking-dot{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.9rem;position:relative;z-index:1;border:3px solid var(--mist-dark);background:white;color:var(--text-muted);transition:all 0.3s;}
    .tracking-step.done .tracking-dot{background:var(--gold);border-color:var(--gold);color:var(--plum-dark);box-shadow:0 0 0 4px rgba(212,162,78,0.18);}
    .tracking-step.active .tracking-dot{background:white;border-color:var(--gold);color:var(--gold);box-shadow:0 0 0 4px rgba(212,162,78,0.18);animation:pulse 1.5s infinite;}
    .tracking-step.cancelled .tracking-dot{background:#a83333;border-color:#a83333;color:white;}
    @keyframes pulse{0%,100%{box-shadow:0 0 0 4px rgba(212,162,78,0.18);}50%{box-shadow:0 0 0 8px rgba(212,162,78,0.1);}}
    .tracking-label{font-size:0.7rem;font-weight:700;text-align:center;margin-top:0.5rem;color:var(--text-muted);}
    .tracking-step.done .tracking-label{color:#a8782e;}
    .tracking-step.active .tracking-label{color:var(--plum);}
    .tracking-step.cancelled .tracking-label{color:#a83333;}
    .tracking-sublabel{font-size:0.65rem;color:var(--text-muted);text-align:center;margin-top:0.15rem;}

    @media(max-width:768px){
        .order-header{flex-direction:column;align-items:flex-start;}
        .order-footer{flex-direction:column;}
        .tracking-label{font-size:0.6rem;}
        .tracking-dot{width:28px;height:28px;font-size:0.75rem;}
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="page-header-title">Riwayat Pesanan</h1>
        <p class="page-header-sub">Pantau status pesanan bukumu di sini</p>
    </div>
</div>

<div class="container">
    @if($orders->count())
    <div class="orders-list">
        @foreach($orders as $i => $order)
        @php
            $statusClass = [
                'pending'   => 'status-pending',
                'paid'      => 'status-paid',
                'shipped'   => 'status-shipped',
                'delivered' => 'status-delivered',
                'cancelled' => 'status-cancelled',
            ][$order->status] ?? 'status-pending';
            $statusLabel = [
                'pending'   => '⏳ Menunggu',
                'paid'      => '✅ Dibayar',
                'shipped'   => '🚚 Dikirim',
                'delivered' => '📦 Diterima',
                'cancelled' => '❌ Dibatalkan',
            ][$order->status] ?? $order->status;

            // Tracking steps
            $isCancelled = $order->status === 'cancelled';
            $steps = [
                ['key' => 'pending',   'icon' => 'fas fa-file-alt',       'label' => 'Pesanan\nDibuat'],
                ['key' => 'paid',      'icon' => 'fas fa-credit-card',     'label' => 'Pembayaran\nDikonfirmasi'],
                ['key' => 'shipped',   'icon' => 'fas fa-shipping-fast',   'label' => 'Dalam\nPengiriman'],
                ['key' => 'delivered', 'icon' => 'fas fa-box-open',        'label' => 'Pesanan\nDiterima'],
            ];
            $order_flow = ['pending','paid','shipped','delivered'];
            $currentIdx = array_search($order->status, $order_flow);
        @endphp
        <div class="order-card reveal" style="transition-delay:{{ $i * 0.08 }}s">
            <div class="order-header">
                <div>
                    <div class="order-code">{{ $order->order_code }}</div>
                    <div class="order-date">{{ $order->created_at->format('d F Y, H:i') }}</div>
                </div>
                <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
            </div>

            <div class="order-body">
                <div class="order-items-list">
                    @php $colors=['#3D2645','#5B4B8A','#8E3B5C','#D4A24E']; @endphp
                    @foreach($order->items as $j => $item)
                    <div class="order-item">
                        <div class="order-item-cover" style="background:linear-gradient(135deg,{{ $colors[$j%4] }},var(--magenta));">📖</div>
                        <div class="order-item-title">
                            {{ $item->product->title ?? 'Produk dihapus' }}
                            <div class="order-item-qty">{{ $item->product->author ?? '' }}</div>
                        </div>
                        <div style="text-align:right;">
                            <div class="order-item-qty">{{ $item->quantity }}x</div>
                            <div class="order-item-price">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- ── TRACKING TIMELINE ── --}}
                <div class="tracking-wrap">
                    <div class="tracking-title"><i class="fas fa-map-marker-alt"></i> Tracking Pesanan</div>
                    @if($isCancelled)
                    <div style="display:flex;align-items:center;gap:0.75rem;background:#f3d8d8;border-radius:12px;padding:1rem 1.25rem;">
                        <div style="width:36px;height:36px;border-radius:50%;background:#a83333;display:flex;align-items:center;justify-content:center;color:white;flex-shrink:0;">
                            <i class="fas fa-times"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;color:#8a2c2c;font-size:0.875rem;">Pesanan Dibatalkan</div>
                            <div style="font-size:0.75rem;color:#a83333;">Pesanan ini telah dibatalkan</div>
                        </div>
                    </div>
                    @else
                    <div class="tracking-steps">
                        @foreach($steps as $sIdx => $step)
                        @php
                            $stepState = 'pending-state'; // default grey
                            if($currentIdx !== false) {
                                if($sIdx < $currentIdx) $stepState = 'done';
                                elseif($sIdx === $currentIdx) $stepState = 'active';
                            } elseif($order->status === 'pending' && $sIdx === 0) {
                                $stepState = 'active';
                            }
                        @endphp
                        <div class="tracking-step {{ $stepState }}">
                            <div class="tracking-dot">
                                <i class="{{ $stepState === 'done' ? 'fas fa-check' : $step['icon'] }}"></i>
                            </div>
                            @foreach(explode('\n', $step['label']) as $line)
                            <div class="{{ $loop->first ? 'tracking-label' : 'tracking-sublabel' }}">{{ $line }}</div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <div class="order-footer">
                <div class="order-info">
                    <div class="order-info-item">
                        <i class="fas fa-credit-card"></i>
                        <strong>{{ $order->payment_method ?? '-' }}</strong>
                    </div>
                    <div class="order-info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <strong>{{ Str::limit($order->shipping_address, 40) }}</strong>
                    </div>
                </div>
                <div>
                    <div class="order-total-label">Total Pembayaran</div>
                    <div class="order-total">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @else
    <div style="padding:3rem 0;">
        <div class="empty-state reveal">
            <div class="empty-state-icon">📦</div>
            <h2 style="font-family:'Playfair Display',serif;font-size:1.8rem;color:var(--plum);margin-bottom:0.75rem;">Belum Ada Pesanan</h2>
            <p style="color:var(--text-muted);margin-bottom:2rem;">Yuk mulai belanja buku impianmu!</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-book-open"></i> Mulai Belanja
            </a>
        </div>
    </div>
    @endif
</div>
@endsection