@extends('layouts.app')
@section('title', 'Daftar')
@push('styles')
<style>
    .auth-wrapper{min-height:calc(100vh - 74px);display:flex;align-items:center;justify-content:center;padding:2rem;background:var(--mist);}
    .auth-container{display:grid;grid-template-columns:1fr 1fr;max-width:900px;width:100%;border-radius:24px;overflow:hidden;box-shadow:var(--shadow-lg);}

    /* LEFT — pakai tema Pustaka Nusantara */
    .auth-left{background:linear-gradient(160deg, var(--plum-dark) 0%, var(--plum) 60%, var(--indigo) 100%);padding:3rem;color:white;display:flex;flex-direction:column;justify-content:space-between;}
    .auth-left-brand{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:900;color:rgba(255,255,255,0.7);margin-bottom:2.5rem;display:flex;align-items:center;gap:8px;}
    .auth-left-brand span{color:var(--gold);}
    .auth-left-title{font-family:'Playfair Display',serif;font-size:2.2rem;font-weight:900;line-height:1.2;margin-bottom:1rem;}
    .auth-left-title em{color:var(--gold);font-style:italic;}
    .auth-left-desc{opacity:0.55;line-height:1.7;font-size:0.875rem;margin-top:0.75rem;}
    .perks{list-style:none;margin-top:2rem;display:flex;flex-direction:column;gap:0.65rem;}
    .perks li{display:flex;align-items:center;gap:0.75rem;font-size:0.85rem;color:rgba(255,255,255,0.7);}
    .perks li::before{content:'✓';background:rgba(212,162,78,0.2);border:0.5px solid rgba(212,162,78,0.35);width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:700;color:var(--gold);flex-shrink:0;}
    .auth-quote{background:rgba(255,255,255,0.05);border:0.5px solid rgba(255,255,255,0.08);border-radius:14px;padding:1.1rem 1.25rem;margin-top:2.5rem;}
    .auth-quote p{font-size:0.82rem;color:rgba(255,255,255,0.5);line-height:1.65;font-style:italic;}
    .auth-quote span{font-size:0.75rem;color:rgba(255,255,255,0.3);margin-top:0.4rem;display:block;font-weight:600;}

    /* RIGHT */
    .auth-right{background:white;padding:3rem;}
    .auth-title{font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:900;color:var(--plum);margin-bottom:0.5rem;}
    .auth-subtitle{color:var(--text-muted);font-size:0.875rem;margin-bottom:2rem;}
    .auth-subtitle a{color:var(--indigo);font-weight:600;text-decoration:none;}
    .auth-subtitle a:hover{color:var(--plum);}
    .auth-footer{text-align:center;margin-top:1.5rem;font-size:0.875rem;color:var(--text-muted);}
    .auth-footer a{color:var(--indigo);font-weight:600;text-decoration:none;}
    .input-wrapper{position:relative;}
    .input-icon{position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:0.85rem;}
    .input-wrapper .form-control{padding-left:2.75rem;}
    .btn-block{width:100%;justify-content:center;}

    @media(max-width:768px){
        .auth-container{grid-template-columns:1fr;}
        .auth-left{display:none;}
    }
</style>
@endpush

@section('content')
<div class="auth-wrapper">
    <div class="auth-container">

        {{-- LEFT --}}
        <div class="auth-left">
            <div>
                <div class="auth-left-brand">
                    <svg width="20" height="20" viewBox="0 0 30 30">
                        <path d="M15 2 L26 22 L20 22 L20 27 L10 27 L10 22 L4 22 Z" fill="#D4A24E"/>
                        <path d="M15 6 L15 22" stroke="#2C1B33" stroke-width="1.5"/>
                        <path d="M9 22 Q15 17 21 22" stroke="#2C1B33" stroke-width="1.5" fill="none"/>
                    </svg>
                    Pustaka <span>Nusantara</span>
                </div>
                <h2 class="auth-left-title">Bergabung<br>Bersama<br><em>Kami!</em></h2>
                <p class="auth-left-desc">Daftar gratis dan nikmati ribuan koleksi buku pilihan terbaik.</p>
                <ul class="perks">
                    <li>Akses ke 500+ koleksi buku</li>
                    <li>Pengiriman cepat ke seluruh Indonesia</li>
                    <li>Riwayat transaksi lengkap</li>
                    <li>Rekomendasi buku personal</li>
                </ul>
            </div>
            <div class="auth-quote">
                <p>"Satu akun, ribuan cerita menantimu."</p>
                <span>— Tim Pustaka Nusantara</span>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="auth-right">
            <h1 class="auth-title">Buat Akun</h1>
            <p class="auth-subtitle">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>

            @if($errors->any())
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" name="name" class="form-control" placeholder="Nama kamu" value="{{ old('name') }}" required autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" name="email" class="form-control" placeholder="email@example.com" value="{{ old('email') }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top:0.5rem;">
                    <i class="fas fa-user-plus"></i> Daftar Sekarang
                </button>
            </form>

            <div class="auth-footer">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk sekarang</a>
            </div>
        </div>

    </div>
</div>
@endsection