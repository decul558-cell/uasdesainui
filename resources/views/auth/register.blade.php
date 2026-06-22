@extends('layouts.app')
@section('title', 'Daftar')
@push('styles')
<style>
    .auth-wrapper{min-height:calc(100vh - 70px);display:flex;align-items:center;justify-content:center;padding:2rem;background:var(--cream);}
    .auth-container{display:grid;grid-template-columns:1fr 1fr;max-width:900px;width:100%;border-radius:24px;overflow:hidden;box-shadow:var(--shadow-lg);}
    .auth-left{background: linear-gradient(160deg, #7C3AED 0%, #DB2777 100%);}
    .auth-left-title{font-family:'Playfair Display',serif;font-size:2.2rem;font-weight:900;line-height:1.2;margin-bottom:1rem;}
    .auth-left-title span{color:#FFE08A;}
    .auth-left-desc{opacity:0.85;line-height:1.7;font-size:0.95rem;}
    .perks{list-style:none;margin-top:2rem;display:flex;flex-direction:column;gap:0.75rem;}
    .perks li{display:flex;align-items:center;gap:0.75rem;font-size:0.9rem;opacity:0.9;}
    .perks li::before{content:'✓';background:rgba(255,255,255,0.2);width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;flex-shrink:0;}
    .auth-right{background:white;padding:3rem;}
    .auth-title{font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:900;color:var(--brown);margin-bottom:0.5rem;}
    .auth-subtitle{color:var(--text-muted);font-size:0.9rem;margin-bottom:2rem;}
    .auth-footer{text-align:center;margin-top:1.5rem;font-size:0.875rem;color:var(--text-muted);}
    .auth-footer a{color:var(--orange);font-weight:600;text-decoration:none;}
    .input-wrapper{position:relative;}
    .input-icon{position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:0.9rem;}
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
        <div class="auth-left">
            <div>
                <div style="font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:900;margin-bottom:2rem;">📚 Toko<span style="color:#FFE08A">Buku</span></div>
                <h2 class="auth-left-title">Bergabung<br>Bersama<br><span>Kami!</span></h2>
                <p class="auth-left-desc" style="margin-top:1rem;">Daftar gratis dan nikmati ribuan koleksi buku pilihan terbaik.</p>
                <ul class="perks">
                    <li>Akses ke 500+ koleksi buku</li>
                    <li>Pengiriman cepat ke seluruh Indonesia</li>
                    <li>Riwayat transaksi lengkap</li>
                    <li>Rekomendasi buku personal</li>
                </ul>
            </div>
            <div style="background:rgba(255,255,255,0.1);border-radius:16px;padding:1.25rem;backdrop-filter:blur(10px);">
                <p style="font-size:0.875rem;opacity:0.9;line-height:1.6;font-style:italic;">"Daftar hanya butuh 1 menit, tapi manfaatnya seumur hidup!"</p>
                <div style="font-size:0.8rem;opacity:0.7;margin-top:0.5rem;font-weight:600;">— Tim TokoBuku</div>
            </div>
        </div>
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