@extends('layouts.app')
@section('title', 'Profil Saya')
@push('styles')
<style>
    .profile-layout{display:grid;grid-template-columns:300px 1fr;gap:2rem;padding:3rem 0;}
    .profile-sidebar{display:flex;flex-direction:column;gap:1.5rem;}
    .profile-card{background:white;border-radius:20px;padding:2rem;box-shadow:var(--shadow);text-align:center;}
    .profile-avatar{width:100px;height:100px;border-radius:50%;object-fit:cover;margin:0 auto 1rem;display:block;border:4px solid var(--mist-dark);}
    .profile-avatar-placeholder{width:100px;height:100px;border-radius:50%;background:linear-gradient(135deg,var(--plum),var(--magenta));display:flex;align-items:center;justify-content:center;color:white;font-size:2.5rem;font-weight:700;margin:0 auto 1rem;}
    .profile-name{font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:700;color:var(--plum);margin-bottom:0.25rem;}
    .profile-email{font-size:0.85rem;color:var(--text-muted);margin-bottom:1rem;}
    .profile-role{display:inline-flex;padding:0.25rem 0.75rem;border-radius:50px;font-size:0.75rem;font-weight:700;}
    .role-admin{background:rgba(212,162,78,0.15);color:#a8782e;}
    .role-user{background:rgba(91,75,138,0.12);color:var(--indigo);}
    .profile-nav{background:white;border-radius:20px;padding:1rem;box-shadow:var(--shadow);}
    .profile-nav-item{display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1rem;border-radius:10px;color:var(--text-muted);text-decoration:none;font-size:0.875rem;font-weight:500;transition:var(--transition);cursor:pointer;border:none;background:none;width:100%;font-family:'Plus Jakarta Sans',sans-serif;}
    .profile-nav-item:hover,.profile-nav-item.active{background:var(--mist);color:var(--magenta);}
    .profile-nav-item i{width:18px;text-align:center;}
    .profile-content{display:flex;flex-direction:column;gap:1.5rem;}
    .profile-section{background:white;border-radius:20px;padding:2rem;box-shadow:var(--shadow);}
    .profile-section-title{font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--plum);margin-bottom:1.5rem;padding-bottom:0.75rem;border-bottom:2px solid var(--mist-dark);}
    .form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}
    .order-mini{display:flex;align-items:center;gap:1rem;padding:0.75rem 0;border-bottom:1px solid var(--mist-dark);}
    .order-mini:last-child{border-bottom:none;}
    .order-mini-code{font-weight:700;color:var(--plum);font-size:0.875rem;}
    .order-mini-date{font-size:0.75rem;color:var(--text-muted);}
    .order-mini-price{font-weight:800;color:var(--magenta);margin-left:auto;font-size:0.9rem;}
    .status-badge{display:inline-flex;align-items:center;gap:0.3rem;padding:0.2rem 0.6rem;border-radius:50px;font-size:0.7rem;font-weight:700;}
    .status-pending{background:#f5e8c9;color:#8a6418;}
    .status-paid{background:#d6ecdf;color:#1d6b3f;}
    .status-shipped{background:#e3ddf0;color:#4a3a7a;}
    .status-delivered{background:#d6ecdf;color:#1d6b3f;}
    .status-cancelled{background:#f3d8d8;color:#8a2c2c;}
    .photo-upload{display:flex;align-items:center;gap:1.5rem;margin-bottom:1.5rem;}
    .photo-preview{width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid var(--mist-dark);}
    @media(max-width:768px){
        .profile-layout{grid-template-columns:1fr;}
        .form-grid-2{grid-template-columns:1fr;}
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="profile-layout">
        <!-- SIDEBAR -->
        <div class="profile-sidebar">
            <div class="profile-card reveal">
                @if($user->photo)
                    <img src="{{ Storage::url($user->photo) }}" alt="{{ $user->name }}" class="profile-avatar">
                @else
                    <div class="profile-avatar-placeholder">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                @endif
                <div class="profile-name">{{ $user->name }}</div>
                <div class="profile-email">{{ $user->email }}</div>
                <span class="profile-role {{ $user->role === 'admin' ? 'role-admin' : 'role-user' }}">
                    {{ $user->role === 'admin' ? '👑 Admin' : '👤 User' }}
                </span>
            </div>

            <div class="profile-nav reveal">
                <a href="#edit-profile" class="profile-nav-item active" onclick="showTab('edit-profile')">
                    <i class="fas fa-user-edit"></i> Edit Profil
                </a>
                <a href="#change-password" class="profile-nav-item" onclick="showTab('change-password')">
                    <i class="fas fa-lock"></i> Ubah Password
                </a>
                <a href="#order-history" class="profile-nav-item" onclick="showTab('order-history')">
                    <i class="fas fa-shopping-bag"></i> Riwayat Pesanan
                </a>
                <a href="{{ route('wishlist.index') }}" class="profile-nav-item">
                    <i class="fas fa-heart"></i> Wishlist Saya
                </a>
                <a href="{{ route('notifications.index') }}" class="profile-nav-item">
                    <i class="fas fa-bell"></i> Notifikasi
                </a>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="profile-content">
            <!-- EDIT PROFILE -->
            <div class="profile-section reveal" id="tab-edit-profile">
                <div class="profile-section-title">Edit Profil</div>
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="photo-upload">
                        @if($user->photo)
                            <img src="{{ Storage::url($user->photo) }}" class="photo-preview" id="photoPreview">
                        @else
                            <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--plum),var(--magenta));display:flex;align-items:center;justify-content:center;color:white;font-size:1.8rem;font-weight:700;" id="photoPreview">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <label for="photoInput" class="btn btn-outline btn-sm" style="cursor:pointer;">
                                <i class="fas fa-camera"></i> Ganti Foto
                            </label>
                            <input type="file" id="photoInput" name="photo" accept="image/*" style="display:none;">
                            <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.35rem;">JPG, PNG. Maks 2MB</p>
                        </div>
                    </div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="+62 812 3456 7890">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control" rows="3" placeholder="Alamat lengkap...">{{ old('address', $user->address) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- CHANGE PASSWORD -->
            <div class="profile-section reveal" id="tab-change-password" style="display:none;">
                <div class="profile-section-title">Ubah Password</div>
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Password Lama</label>
                        <input type="password" name="current_password" class="form-control" placeholder="••••••••" required>
                        @error('current_password')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-lock"></i> Ubah Password
                    </button>
                </form>
            </div>

            <!-- ORDER HISTORY -->
            <div class="profile-section reveal" id="tab-order-history" style="display:none;">
                <div class="profile-section-title">Riwayat Pesanan Terbaru</div>
                @if($orders->count())
                @php $sc=['pending'=>'status-pending','paid'=>'status-paid','shipped'=>'status-shipped','delivered'=>'status-delivered','cancelled'=>'status-cancelled']; $sl=['pending'=>'Menunggu','paid'=>'Dibayar','shipped'=>'Dikirim','delivered'=>'Diterima','cancelled'=>'Batal']; @endphp
                @foreach($orders as $order)
                <div class="order-mini">
                    <div>
                        <div class="order-mini-code">{{ $order->order_code }}</div>
                        <div class="order-mini-date">{{ $order->created_at->format('d M Y') }} · {{ $order->items->count() }} buku</div>
                    </div>
                    <span class="status-badge {{ $sc[$order->status] ?? '' }}">{{ $sl[$order->status] ?? $order->status }}</span>
                    <div class="order-mini-price">Rp {{ number_format($order->total_price - $order->discount, 0, ',', '.') }}</div>
                </div>
                @endforeach
                <div style="margin-top:1rem;">
                    <a href="{{ route('orders.index') }}" class="btn btn-outline btn-sm">Lihat Semua Pesanan</a>
                </div>
                @else
                <p style="color:var(--text-muted);text-align:center;padding:2rem 0;">Belum ada pesanan.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showTab(tab) {
        document.querySelectorAll('[id^="tab-"]').forEach(el => el.style.display = 'none');
        document.getElementById('tab-' + tab).style.display = 'block';
        document.querySelectorAll('.profile-nav-item').forEach(el => el.classList.remove('active'));
        event.target.closest('.profile-nav-item').classList.add('active');
    }

    document.getElementById('photoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('photoPreview');
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'photo-preview';
                img.id = 'photoPreview';
                preview.replaceWith(img);
            }
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush