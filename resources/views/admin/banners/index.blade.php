@extends('layouts.admin')
@section('title', 'Kelola Banner')

@section('content')
<div class="page-header">
    <div class="page-title">Kelola Banner</div>
    <div class="page-sub">Atur banner yang tampil di halaman home.</div>
</div>

<div style="display:grid;grid-template-columns:1fr 380px;gap:1.5rem;">
    <!-- LIST -->
    <div>
        @if($banners->count())
        <div style="display:flex;flex-direction:column;gap:1rem;">
            @foreach($banners as $banner)
            <div class="admin-card reveal" style="margin-bottom:0;">
                <div style="display:flex;gap:1.5rem;align-items:center;flex-wrap:wrap;">
                    <!-- PREVIEW -->
                    <div style="width:160px;height:90px;border-radius:10px;background:{{ $banner->bg_color }};flex-shrink:0;display:flex;align-items:center;justify-content:center;overflow:hidden;position:relative;">
                        @if($banner->image)
                            <img src="{{ Storage::url($banner->image) }}" style="width:100%;height:100%;object-fit:cover;">
                        @endif
                        <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:0.75rem;text-align:center;">
                            <div style="font-family:'Playfair Display',serif;font-size:0.8rem;font-weight:700;color:white;text-shadow:0 1px 3px rgba(0,0,0,0.5);">{{ Str::limit($banner->title, 30) }}</div>
                        </div>
                    </div>
                    <div style="flex:1;">
                        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.35rem;flex-wrap:wrap;">
                            <span style="font-weight:700;color:var(--brown);">{{ $banner->title }}</span>
                            @if($banner->is_active)
                                <span style="background:#ECFDF5;color:#065F46;padding:0.2rem 0.6rem;border-radius:50px;font-size:0.7rem;font-weight:700;">Aktif</span>
                            @else
                                <span style="background:#F3F4F6;color:#6B7280;padding:0.2rem 0.6rem;border-radius:50px;font-size:0.7rem;font-weight:700;">Nonaktif</span>
                            @endif
                        </div>
                        @if($banner->subtitle)
                        <p style="font-size:0.8rem;color:var(--text-muted);margin-bottom:0.5rem;">{{ Str::limit($banner->subtitle, 60) }}</p>
                        @endif
                        @if($banner->button_text)
                        <span style="font-size:0.75rem;color:var(--orange);font-weight:600;"><i class="fas fa-link"></i> {{ $banner->button_text }}</span>
                        @endif
                    </div>
                    <div style="display:flex;gap:0.5rem;flex-shrink:0;">
                        <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-del" onclick="return confirm('Hapus banner ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="admin-card" style="text-align:center;padding:3rem;">
            <div style="font-size:3rem;margin-bottom:1rem;">🖼️</div>
            <h3 style="font-family:'Playfair Display',serif;color:var(--brown);margin-bottom:0.5rem;">Belum Ada Banner</h3>
            <p style="color:var(--text-muted);">Buat banner pertamamu di form sebelah.</p>
        </div>
        @endif
    </div>

    <!-- FORM -->
    <div>
        <div class="admin-card reveal">
            <div class="admin-card-title">Tambah Banner Baru</div>
            <form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">Judul Banner <span style="color:#ef4444">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="Judul banner..." required>
                </div>
                <div class="form-group">
                    <label class="form-label">Subtitle</label>
                    <textarea name="subtitle" class="form-control" rows="2" placeholder="Deskripsi singkat..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Warna Background</label>
                    <div style="display:flex;gap:0.75rem;align-items:center;">
                        <input type="color" name="bg_color" value="#1A2E3F" style="width:50px;height:40px;border:none;border-radius:8px;cursor:pointer;padding:2px;">
                        <span style="font-size:0.8rem;color:var(--text-muted);">Pilih warna latar banner</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Gambar Banner</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.35rem;">JPG, PNG. Maks 2MB. Ukuran ideal: 1200x400px</p>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Teks Tombol</label>
                        <input type="text" name="button_text" class="form-control" placeholder="Shop Now">
                    </div>
                    <div class="form-group">
                        <label class="form-label">URL Tombol</label>
                        <input type="text" name="button_url" class="form-control" placeholder="/katalog">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Urutan Tampil</label>
                    <input type="number" name="order" class="form-control" value="0" min="0">
                </div>
                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:0.75rem;cursor:pointer;">
                        <input type="checkbox" name="is_active" checked style="width:16px;height:16px;accent-color:var(--orange);">
                        <span style="font-weight:600;color:var(--brown);font-size:0.875rem;">Aktifkan banner</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                    <i class="fas fa-plus"></i> Tambah Banner
                </button>
            </form>
        </div>
    </div>
</div>
@endsection