@extends('layouts.app')
@section('title', isset($coupon) ? 'Edit Kupon' : 'Buat Kupon')
@push('styles')
<style>
    .admin-wrapper{display:grid;grid-template-columns:260px 1fr;min-height:calc(100vh - 70px);}
    .admin-sidebar{background:var(--brown);color:white;padding:2rem 0;position:sticky;top:70px;height:calc(100vh - 70px);overflow-y:auto;}
    .admin-sidebar-brand{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;padding:0 1.5rem 1.5rem;border-bottom:1px solid rgba(255,255,255,0.1);margin-bottom:1rem;}
    .admin-sidebar-brand span{color:var(--orange);}
    .admin-nav-label{font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.4);padding:0.75rem 1.5rem 0.35rem;}
    .admin-nav-item{display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1.5rem;color:rgba(255,255,255,0.7);text-decoration:none;transition:var(--transition);font-size:0.9rem;font-weight:500;position:relative;}
    .admin-nav-item:hover{background:rgba(255,255,255,0.08);color:white;}
    .admin-nav-item.active{background:rgba(232,98,42,0.2);color:var(--orange);}
    .admin-nav-item.active::before{content:'';position:absolute;left:0;top:0;bottom:0;width:3px;background:var(--orange);border-radius:0 4px 4px 0;}
    .admin-nav-item i{width:18px;text-align:center;}
    .admin-content{padding:2rem;background:var(--cream);}
    .admin-page-title{font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:900;color:var(--brown);margin-bottom:0.35rem;}
    .admin-page-sub{color:var(--text-muted);font-size:0.9rem;margin-bottom:2rem;}
    .form-layout{display:grid;grid-template-columns:1fr 320px;gap:1.5rem;}
    .admin-card{background:white;border-radius:16px;padding:1.5rem;box-shadow:var(--shadow);margin-bottom:1.5rem;}
    .admin-card-title{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:var(--brown);margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:2px solid var(--cream-dark);}
    .form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}
    select.form-control{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%238B7355' d='M6 8L1 3h10z'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 1rem center;padding-right:2.5rem;}
    .coupon-preview{background:linear-gradient(135deg,var(--brown),var(--brown-mid));border-radius:16px;padding:2rem;color:white;text-align:center;margin-bottom:1.5rem;}
    .coupon-preview-code{font-family:monospace;font-size:2rem;font-weight:900;letter-spacing:0.2em;color:var(--gold);margin-bottom:0.5rem;}
    .coupon-preview-value{font-size:1.1rem;opacity:0.85;}
    .toggle-switch{display:flex;align-items:center;gap:0.75rem;cursor:pointer;}
    .toggle-input{display:none;}
    .toggle-track{width:48px;height:26px;background:var(--cream-dark);border-radius:50px;position:relative;transition:var(--transition);}
    .toggle-input:checked + .toggle-track{background:var(--orange);}
    .toggle-thumb{width:20px;height:20px;background:white;border-radius:50%;position:absolute;top:3px;left:3px;transition:var(--transition);box-shadow:0 2px 4px rgba(0,0,0,0.2);}
    .toggle-input:checked + .toggle-track .toggle-thumb{left:25px;}
    @media(max-width:768px){
        .admin-wrapper{grid-template-columns:1fr;}
        .admin-sidebar{display:none;}
        .form-layout{grid-template-columns:1fr;}
        .form-grid-2{grid-template-columns:1fr;}
    }
</style>
@endpush

@section('content')
<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="admin-sidebar-brand">⚙️ Admin<span>Panel</span></div>
        <div class="admin-nav-label">Menu Utama</div>
        <a href="{{ route('admin.dashboard') }}" class="admin-nav-item"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <div class="admin-nav-label">Kelola</div>
        <a href="{{ route('admin.products.index') }}" class="admin-nav-item"><i class="fas fa-book"></i> Produk</a>
        <a href="{{ route('admin.categories.index') }}" class="admin-nav-item"><i class="fas fa-tags"></i> Kategori</a>
        <a href="{{ route('admin.articles.index') }}" class="admin-nav-item"><i class="fas fa-newspaper"></i> Artikel</a>
        <a href="{{ route('admin.users.index') }}" class="admin-nav-item"><i class="fas fa-users"></i> Pengguna</a>
        <a href="{{ route('admin.orders.index') }}" class="admin-nav-item"><i class="fas fa-shopping-bag"></i> Transaksi</a>
        <a href="{{ route('admin.coupons.index') }}" class="admin-nav-item active"><i class="fas fa-ticket-alt"></i> Kupon</a>
        <a href="{{ route('admin.reports.index') }}" class="admin-nav-item"><i class="fas fa-chart-bar"></i> Laporan</a>
        <a href="{{ route('admin.activity-logs.index') }}" class="admin-nav-item"><i class="fas fa-history"></i> Log Aktivitas</a>
        <div class="admin-nav-label">Lainnya</div>
        <a href="{{ route('home') }}" class="admin-nav-item"><i class="fas fa-store"></i> Lihat Toko</a>
        <form method="POST" action="{{ route('logout') }}" style="padding:0 0.75rem;margin-top:0.25rem;">
            @csrf
            <button type="submit" class="admin-nav-item" style="width:100%;background:none;border:none;cursor:pointer;color:rgba(255,255,255,0.7);font-family:'Plus Jakarta Sans',sans-serif;">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </form>
    </aside>

    <main class="admin-content">
        <div class="admin-page-title">{{ isset($coupon) ? 'Edit Kupon' : 'Buat Kupon Baru' }}</div>
        <div class="admin-page-sub">{{ isset($coupon) ? 'Perbarui detail kupon.' : 'Buat kode kupon diskon baru.' }}</div>

        @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:1.5rem;">
            <i class="fas fa-exclamation-circle"></i>
            <ul style="margin:0;padding-left:1rem;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ isset($coupon) ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}">
            @csrf
            @if(isset($coupon)) @method('PUT') @endif

            <div class="form-layout">
                <div>
                    <div class="admin-card reveal">
                        <div class="admin-card-title">Detail Kupon</div>

                        <div class="form-group">
                            <label class="form-label">Kode Kupon <span style="color:#ef4444">*</span></label>
                            <input type="text" name="code" class="form-control"
                                value="{{ old('code', isset($coupon) ? $coupon->code : '') }}"
                                placeholder="CONTOH: DISKON50"
                                style="font-family:monospace;font-size:1.1rem;letter-spacing:0.1em;text-transform:uppercase;"
                                oninput="updatePreview()" required>
                            <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.35rem;">Kode akan otomatis diubah ke huruf kapital.</p>
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label class="form-label">Tipe Diskon <span style="color:#ef4444">*</span></label>
                                <select name="type" class="form-control" onchange="updatePreview()" required>
                                    <option value="percent" {{ old('type', $coupon->type ?? '') === 'percent' ? 'selected' : '' }}>Persen (%)</option>
                                    <option value="fixed" {{ old('type', $coupon->type ?? '') === 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nilai Diskon <span style="color:#ef4444">*</span></label>
                                <input type="number" name="value" class="form-control"
                                    value="{{ old('value', $coupon->value ?? '') }}"
                                    placeholder="10" min="1" oninput="updatePreview()" required>
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label class="form-label">Minimum Order (Rp)</label>
                                <input type="number" name="min_order" class="form-control"
                                    value="{{ old('min_order', $coupon->min_order ?? 0) }}"
                                    placeholder="0" min="0">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Maksimal Penggunaan</label>
                                <input type="number" name="max_uses" class="form-control"
                                    value="{{ old('max_uses', $coupon->max_uses ?? 100) }}"
                                    placeholder="100" min="1">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tanggal Kadaluarsa</label>
                            <input type="datetime-local" name="expired_at" class="form-control"
                                value="{{ old('expired_at', isset($coupon) && $coupon->expired_at ? $coupon->expired_at->format('Y-m-d\TH:i') : '') }}">
                            <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.35rem;">Kosongkan jika tidak ada batas waktu.</p>
                        </div>

                        @if(isset($coupon))
                        <div class="form-group">
                            <label class="toggle-switch">
                                <input type="checkbox" name="is_active" class="toggle-input" id="toggleActive" {{ $coupon->is_active ? 'checked' : '' }}>
                                <div class="toggle-track"><div class="toggle-thumb"></div></div>
                                <span style="font-weight:600;color:var(--brown);">Kupon Aktif</span>
                            </label>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- SIDEBAR -->
                <div>
                    <div class="coupon-preview" id="couponPreview">
                        <div style="font-size:0.8rem;opacity:0.7;margin-bottom:0.5rem;text-transform:uppercase;letter-spacing:0.1em;">Kode Kupon</div>
                        <div class="coupon-preview-code" id="previewCode">{{ isset($coupon) ? $coupon->code : 'KODE' }}</div>
                        <div class="coupon-preview-value" id="previewValue">
                            {{ isset($coupon) ? ($coupon->type === 'percent' ? 'Diskon ' . $coupon->value . '%' : 'Diskon Rp ' . number_format($coupon->value, 0, ',', '.')) : 'Nilai Diskon' }}
                        </div>
                        <div style="margin-top:1rem;font-size:0.8rem;opacity:0.6;">🎫 TokoBuku</div>
                    </div>

                    <div class="admin-card reveal">
                        <div class="admin-card-title">Aksi</div>
                        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-bottom:0.75rem;">
                            <i class="fas fa-save"></i> {{ isset($coupon) ? 'Simpan Perubahan' : 'Buat Kupon' }}
                        </button>
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline" style="width:100%;justify-content:center;">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </main>
</div>
@endsection

@push('scripts')
<script>
    function updatePreview() {
        const code = document.querySelector('[name="code"]').value.toUpperCase() || 'KODE';
        const type = document.querySelector('[name="type"]').value;
        const value = document.querySelector('[name="value"]').value || '0';

        document.getElementById('previewCode').textContent = code;
        document.getElementById('previewValue').textContent = type === 'percent'
            ? 'Diskon ' + value + '%'
            : 'Diskon Rp ' + parseInt(value).toLocaleString('id-ID');
    }
</script>
@endpush