@extends('layouts.app')
@section('title', 'Checkout')
@push('styles')
<style>
    .page-header{background: linear-gradient(135deg, #3D2645 0%, #5B4B8A 100%);padding:3rem 2rem;color:white;text-align:center;}
    .page-header-title{font-family:'Playfair Display',serif;font-size:clamp(2rem,4vw,3rem);font-weight:900;margin-bottom:0.5rem;}
    .page-header-sub{opacity:0.8;font-size:0.95rem;}
    .checkout-layout{display:grid;grid-template-columns:1fr 380px;gap:2rem;padding:3rem 0;}
    .checkout-form{background:white;border-radius:20px;padding:2.5rem;box-shadow:var(--shadow);}
    .checkout-section-title{font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:700;color:var(--plum);margin-bottom:1.5rem;padding-bottom:0.75rem;border-bottom:2px solid var(--mist-dark);display:flex;align-items:center;gap:0.75rem;}
    .payment-options{display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;margin-top:0.5rem;}
    .payment-option{position:relative;}
    .payment-option input{position:absolute;opacity:0;width:0;height:0;}
    .payment-label{display:flex;align-items:center;gap:0.75rem;padding:1rem;border:2px solid var(--mist-dark);border-radius:12px;cursor:pointer;transition:var(--transition);font-weight:500;font-size:0.9rem;}
    .payment-label:hover{border-color:var(--gold);background:rgba(212,162,78,0.05);}
    .payment-option input:checked + .payment-label{border-color:var(--magenta);background:rgba(142,59,92,0.06);color:var(--magenta);}
    .payment-icon{font-size:1.5rem;}
    .order-summary{background:white;border-radius:20px;padding:2rem;box-shadow:var(--shadow);height:fit-content;position:sticky;top:90px;}
    .summary-title{font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:700;color:var(--plum);margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:2px solid var(--mist-dark);}
    .order-item{display:flex;gap:1rem;padding:0.75rem 0;border-bottom:1px solid var(--mist-dark);}
    .order-item:last-of-type{border-bottom:none;}
    .order-item-cover{width:50px;height:65px;border-radius:8px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:1.5rem;}
    .order-item-info{flex:1;}
    .order-item-title{font-weight:700;color:var(--plum);font-size:0.875rem;line-height:1.3;margin-bottom:0.25rem;}
    .order-item-qty{font-size:0.775rem;color:var(--text-muted);}
    .order-item-price{font-weight:800;color:var(--magenta);font-size:0.9rem;white-space:nowrap;}
    .summary-divider{border:none;border-top:2px solid var(--mist-dark);margin:1rem 0;}
    .summary-row{display:flex;justify-content:space-between;padding:0.4rem 0;font-size:0.9rem;}
    .summary-total{display:flex;justify-content:space-between;padding:1rem 0 0;border-top:2px solid var(--mist-dark);margin-top:0.5rem;}
    .total-label{font-weight:700;color:var(--plum);font-size:1rem;}
    .total-price{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:900;color:var(--magenta);}
    .steps{display:flex;align-items:center;justify-content:center;gap:0;margin-bottom:2rem;}
    .step{display:flex;align-items:center;gap:0.5rem;}
    .step-num{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.85rem;}
    .step.done .step-num{background:var(--gold);color:var(--plum-dark);}
    .step.active .step-num{background:var(--plum);color:white;}
    .step.pending .step-num{background:var(--mist-dark);color:var(--text-muted);}
    .step-label{font-size:0.8rem;font-weight:600;}
    .step.active .step-label{color:var(--plum);}
    .step.pending .step-label{color:var(--text-muted);}
    .step-line{width:40px;height:2px;background:var(--mist-dark);margin:0 0.25rem;}
    .step-line.done{background:var(--gold);}
    @media(max-width:768px){
        .checkout-layout{grid-template-columns:1fr;}
        .order-summary{position:static;}
        .payment-options{grid-template-columns:1fr;}
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="page-header-title">Checkout</h1>
        <p class="page-header-sub">Lengkapi informasi pengiriman dan pembayaran</p>
    </div>
</div>

<div class="container">
    <!-- STEPS -->
    <div class="steps" style="padding-top:2rem;">
        <div class="step done">
            <div class="step-num"><i class="fas fa-check" style="font-size:0.75rem"></i></div>
            <span class="step-label">Keranjang</span>
        </div>
        <div class="step-line done"></div>
        <div class="step active">
            <div class="step-num">2</div>
            <span class="step-label">Checkout</span>
        </div>
        <div class="step-line"></div>
        <div class="step pending">
            <div class="step-num">3</div>
            <span class="step-label" style="color:var(--text-muted);">Selesai</span>
        </div>
    </div>

    <div class="checkout-layout">
        <!-- FORM -->
        <div class="checkout-form reveal">
            <form method="POST" action="{{ route('orders.store') }}">
                @csrf

                <div class="checkout-section-title">
                    <i class="fas fa-map-marker-alt" style="color:var(--magenta)"></i>
                    Alamat Pengiriman
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Penerima</label>
                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly style="background:var(--mist);">
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat Lengkap <span style="color:#a83333">*</span></label>
                    <textarea name="shipping_address" class="form-control" rows="4"
                        placeholder="Jl. Nama Jalan No. XX, Kelurahan, Kecamatan, Kota, Provinsi, Kode Pos"
                        required>{{ old('shipping_address') }}</textarea>
                    @error('shipping_address')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="checkout-section-title" style="margin-top:2rem;">
                    <i class="fas fa-credit-card" style="color:var(--magenta)"></i>
                    Metode Pembayaran
                </div>

                <div class="payment-options">
                    <div class="payment-option">
                        <input type="radio" name="payment_method" id="bca" value="Transfer BCA" checked>
                        <label class="payment-label" for="bca">
                            <span class="payment-icon">🏦</span>
                            <div>
                                <div style="font-weight:700;">Transfer BCA</div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">Bank Central Asia</div>
                            </div>
                        </label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" name="payment_method" id="mandiri" value="Transfer Mandiri">
                        <label class="payment-label" for="mandiri">
                            <span class="payment-icon">🏦</span>
                            <div>
                                <div style="font-weight:700;">Transfer Mandiri</div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">Bank Mandiri</div>
                            </div>
                        </label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" name="payment_method" id="gopay" value="GoPay">
                        <label class="payment-label" for="gopay">
                            <span class="payment-icon">💚</span>
                            <div>
                                <div style="font-weight:700;">GoPay</div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">Dompet Digital</div>
                            </div>
                        </label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" name="payment_method" id="cod" value="COD">
                        <label class="payment-label" for="cod">
                            <span class="payment-icon">💵</span>
                            <div>
                                <div style="font-weight:700;">COD</div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">Bayar di Tempat</div>
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;margin-top:2rem;">
                    <i class="fas fa-check-circle"></i> Konfirmasi Pesanan
                </button>
            </form>
        </div>

        <!-- ORDER SUMMARY -->
        <div class="order-summary reveal">
            <div class="summary-title">Ringkasan Pesanan</div>
            @php $colors=['#3D2645','#5B4B8A','#8E3B5C','#D4A24E']; @endphp
            @foreach($carts as $i => $cart)
            <div class="order-item">
                <div class="order-item-cover" style="background:linear-gradient(135deg,{{ $colors[$i%4] }},var(--magenta));">📖</div>
                <div class="order-item-info">
                    <div class="order-item-title">{{ $cart->product->title }}</div>
                    <div class="order-item-qty">{{ $cart->product->author }} · {{ $cart->quantity }}x</div>
                </div>
                <div class="order-item-price">Rp {{ number_format($cart->product->price * $cart->quantity, 0, ',', '.') }}</div>
            </div>
            @endforeach

            <div class="summary-row" style="margin-top:1rem;">
                <span style="color:var(--text-muted);">Subtotal</span>
                <span style="font-weight:700;">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span style="color:var(--text-muted);">Ongkos Kirim</span>
                <span style="font-weight:700;color:#1d6b3f;">Gratis</span>
            </div>
            <div class="summary-total">
                <span class="total-label">Total</span>
                <span class="total-price">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection