@extends('layouts.app')
@section('title', 'Masuk')
@push('styles')
<style>
    .auth-wrapper{min-height:calc(100vh - 70px);display:flex;align-items:center;justify-content:center;padding:2rem;background:var(--cream);}
    .auth-container{display:grid;grid-template-columns:1fr 1fr;max-width:900px;width:100%;border-radius:24px;overflow:hidden;box-shadow:var(--shadow-lg);}
    .auth-left{background: linear-gradient(160deg, #0F2544 0%, #0D9488 100%);}
    .auth-left::before{content:'📚';position:absolute;font-size:15rem;opacity:0.05;bottom:-3rem;right:-3rem;}
    .auth-left-title{font-family:'Playfair Display',serif;font-size:2.2rem;font-weight:900;line-height:1.2;margin-bottom:1rem;}
    .auth-left-title span{color:var(--gold);}
    .auth-left-desc{opacity:0.8;line-height:1.7;font-size:0.95rem;}
    .auth-testimonial{background:rgba(255,255,255,0.1);border-radius:16px;padding:1.25rem;backdrop-filter:blur(10px);}
    .auth-testimonial p{font-size:0.875rem;opacity:0.9;line-height:1.6;font-style:italic;}
    .auth-testimonial-author{font-size:0.8rem;opacity:0.7;margin-top:0.5rem;font-weight:600;}
    .auth-right{background:white;padding:3rem;}
    .auth-title{font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:900;color:var(--brown);margin-bottom:0.5rem;}
    .auth-subtitle{color:var(--text-muted);font-size:0.9rem;margin-bottom:2rem;}
    .auth-divider{display:flex;align-items:center;gap:1rem;margin:1.5rem 0;}
    .auth-divider::before,.auth-divider::after{content:'';flex:1;height:1px;background:var(--cream-dark);}
    .auth-divider span{font-size:0.8rem;color:var(--text-muted);white-space:nowrap;}
    .auth-footer{text-align:center;margin-top:1.5rem;font-size:0.875rem;color:var(--text-muted);}
    .auth-footer a{color:var(--orange);font-weight:600;text-decoration:none;}
    .auth-footer a:hover{text-decoration:underline;}
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
                <div style="font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:900;margin-bottom:2rem;">📚 Toko<span style="color:var(--gold)">Buku</span></div>
                <h2 class="auth-left-title">Selamat<br>Datang<br><span>Kembali!</span></h2>
                <p class="auth-left-desc" style="margin-top:1rem;">Masuk ke akun kamu dan lanjutkan perjalanan membacamu bersama kami.</p>
            </div>
            <div class="auth-testimonial">
                <p>"TokoBuku adalah surga bagi para pecinta buku. Koleksinya lengkap dan pengiriman cepat!"</p>
                <div class="auth-testimonial-author">— Andy, Mahasiswa Surabaya</div>
            </div>
        </div>
        <div class="auth-right">
            <h1 class="auth-title">Masuk</h1>
            <p class="auth-subtitle">Belum punya akun? <a href="{{ route('register') }}">Daftar gratis</a></p>

            @if($errors->any())
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" name="email" class="form-control" placeholder="email@example.com" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
                    <label style="display:flex;align-items:center;gap:0.5rem;font-size:0.875rem;cursor:pointer;">
                        <input type="checkbox" name="remember"> Ingat saya
                    </label>
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>
            </form>

            <div class="auth-divider"><span>info akun demo</span></div>
            <div style="background:var(--cream);border-radius:var(--radius);padding:1rem;font-size:0.8rem;color:var(--text-muted);">
                <div style="margin-bottom:0.35rem;"><strong>Admin:</strong> admin@tokobuku.com / password</div>
                <div><strong>User:</strong> andy@tokobuku.com / password</div>
            </div>

            <div class="auth-footer">
                Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
            </div>
        </div>
    </div>
</div>
@endsection