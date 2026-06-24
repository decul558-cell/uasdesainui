@extends('layouts.app')
@section('title', 'Masuk')
@push('styles')
<style>
    .auth-wrapper{min-height:calc(100vh - 70px);display:flex;align-items:center;justify-content:center;padding:2rem;background:var(--mist);}
    .auth-container{display:grid;grid-template-columns:1fr 1fr;max-width:900px;width:100%;border-radius:24px;overflow:hidden;box-shadow:var(--shadow-lg);}
    .auth-left{background:linear-gradient(160deg,#3D2645 0%,#5B4B8A 100%);position:relative;padding:3rem;display:flex;flex-direction:column;justify-content:space-between;color:white;overflow:hidden;}
    .auth-left::before{content:'📚';position:absolute;font-size:15rem;opacity:0.06;bottom:-3rem;right:-3rem;}
    .auth-left-title{font-family:'Playfair Display',serif;font-size:2.2rem;font-weight:900;line-height:1.2;margin-bottom:1rem;position:relative;z-index:1;}
    .auth-left-title span{color:var(--gold);}
    .auth-left-desc{opacity:0.85;line-height:1.7;font-size:0.95rem;position:relative;z-index:1;}
    .auth-testimonial{background:rgba(255,255,255,0.08);border-radius:16px;padding:1.25rem;backdrop-filter:blur(10px);position:relative;z-index:1;border:1px solid rgba(255,255,255,0.1);}
    .auth-testimonial p{font-size:0.875rem;opacity:0.92;line-height:1.6;font-style:italic;color:white;}
    .auth-testimonial-author{font-size:0.8rem;opacity:0.75;margin-top:0.5rem;font-weight:600;color:#d9c9e0;}
    .auth-right{background:white;padding:3rem;}
    .auth-title{font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:900;color:var(--plum);margin-bottom:0.5rem;}
    .auth-subtitle{color:var(--text-muted);font-size:0.9rem;margin-bottom:2rem;}
    .auth-subtitle a{color:var(--magenta);font-weight:600;text-decoration:none;}
    .auth-subtitle a:hover{text-decoration:underline;}
    .auth-footer{text-align:center;margin-top:1.5rem;font-size:0.875rem;color:var(--text-muted);}
    .auth-footer a{color:var(--magenta);font-weight:600;text-decoration:none;}
    .auth-footer a:hover{text-decoration:underline;}
    .input-wrapper{position:relative;}
    .input-icon{position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:0.9rem;pointer-events:none;}
    .input-wrapper .form-control{padding-left:2.75rem;}
    .btn-block{width:100%;justify-content:center;}

    /* Tombol toggle password */
    .btn-eye{
        position:absolute;
        right:1rem;
        top:50%;
        transform:translateY(-50%);
        background:none;
        border:none;
        cursor:pointer;
        color:var(--text-muted);
        font-size:0.95rem;
        padding:0;
        line-height:1;
        transition:color 0.2s;
    }
    .btn-eye:hover{color:var(--plum);}
    .input-wrapper .form-control.has-eye{padding-right:2.75rem;}

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
                <div style="display:flex;align-items:center;gap:0.5rem;font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:900;margin-bottom:2rem;position:relative;z-index:1;">
                    <svg width="24" height="24" viewBox="0 0 30 30" style="flex-shrink:0;">
                        <path d="M15 2 L26 22 L20 22 L20 27 L10 27 L10 22 L4 22 Z" fill="#D4A24E"/>
                        <path d="M15 6 L15 22" stroke="#2C1B33" stroke-width="1.5"/>
                        <path d="M9 22 Q15 17 21 22" stroke="#2C1B33" stroke-width="1.5" fill="none"/>
                    </svg>
                    Pustaka <span style="color:var(--gold)">Nusantara</span>
                </div>
                <h2 class="auth-left-title">Selamat<br>Datang<br><span>Kembali!</span></h2>
                <p class="auth-left-desc" style="margin-top:1rem;">Masuk ke akun kamu dan lanjutkan perjalanan membacamu bersama kami.</p>
            </div>
            <div class="auth-testimonial">
                <p style="font-style:normal;">📖 Ribuan cerita menanti untuk dibuka. Yang mana akan jadi favoritmu selanjutnya?</p>
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
                        <input type="email" name="email" class="form-control"
                            placeholder="email@example.com"
                            value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password" id="passwordInput"
                            class="form-control has-eye"
                            placeholder="••••••••" required>
                        <button type="button" class="btn-eye" id="btnEye" onclick="togglePassword()" aria-label="Tampilkan password">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
                    <label style="display:flex;align-items:center;gap:0.5rem;font-size:0.875rem;cursor:pointer;color:var(--text);">
                        <input type="checkbox" name="remember"> Ingat saya
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>
            </form>

            <div class="auth-footer">
                Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endpush