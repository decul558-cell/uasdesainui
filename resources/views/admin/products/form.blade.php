@extends('layouts.admin')
@section('title', isset($product) ? 'Edit Produk' : 'Tambah Produk')
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
    .admin-card{background:white;border-radius:16px;padding:1.5rem;box-shadow:var(--shadow);}
    .admin-card-title{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:var(--brown);margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:2px solid var(--cream-dark);}
    .form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}
    .cover-preview{width:100%;aspect-ratio:3/4;border-radius:12px;background:linear-gradient(135deg,var(--cream-dark),var(--cream));display:flex;flex-direction:column;align-items:center;justify-content:center;border:2px dashed var(--cream-dark);transition:var(--transition);overflow:hidden;cursor:pointer;position:relative;}
    .cover-preview:hover{border-color:var(--orange);}
    .cover-preview img{width:100%;height:100%;object-fit:cover;position:absolute;}
    .cover-preview-text{text-align:center;color:var(--text-muted);z-index:1;}
    .cover-preview-icon{font-size:3rem;margin-bottom:0.5rem;}
    .cover-preview-label{font-size:0.8rem;font-weight:600;}
    select.form-control{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%238B7355' d='M6 8L1 3h10z'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 1rem center;padding-right:2.5rem;}
    textarea.form-control{resize:vertical;min-height:120px;}
    @media(max-width:768px){
        .admin-wrapper{grid-template-columns:1fr;}
        .admin-sidebar{display:none;}
        .form-layout{grid-template-columns:1fr;}
        .form-grid-2{grid-template-columns:1fr;}
    }
</style>
@endpush

@section('content')
<div>
        <div class="admin-page-title">{{ isset($product) ? 'Edit Produk' : 'Tambah Produk' }}</div>
        <div class="admin-page-sub">{{ isset($product) ? 'Perbarui informasi buku.' : 'Tambahkan buku baru ke katalog.' }}</div>

        @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:1.5rem;">
            <i class="fas fa-exclamation-circle"></i>
            <ul style="margin:0;padding-left:1rem;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form method="POST"
            action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}"
            enctype="multipart/form-data">
            @csrf
            @if(isset($product)) @method('PUT') @endif

            <div class="form-layout">
                <!-- MAIN FORM -->
                <div>
                    <div class="admin-card reveal" style="margin-bottom:1.5rem;">
                        <div class="admin-card-title">Informasi Buku</div>

                        <div class="form-group">
                            <label class="form-label">Judul Buku <span style="color:#ef4444">*</span></label>
                            <input type="text" name="title" class="form-control"
                                value="{{ old('title', $product->title ?? '') }}"
                                placeholder="Masukkan judul buku" required>
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label class="form-label">Penulis <span style="color:#ef4444">*</span></label>
                                <input type="text" name="author" class="form-control"
                                    value="{{ old('author', $product->author ?? '') }}"
                                    placeholder="Nama penulis" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Penerbit</label>
                                <input type="text" name="publisher" class="form-control"
                                    value="{{ old('publisher', $product->publisher ?? '') }}"
                                    placeholder="Nama penerbit">
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label class="form-label">Kategori <span style="color:#ef4444">*</span></label>
                                <select name="category_id" class="form-control" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tahun Terbit</label>
                                <input type="number" name="year" class="form-control"
                                    value="{{ old('year', $product->year ?? '') }}"
                                    placeholder="2024" min="1900" max="2099">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">ISBN</label>
                            <input type="text" name="isbn" class="form-control"
                                value="{{ old('isbn', $product->isbn ?? '') }}"
                                placeholder="978-xxx-xxx-xxx-x">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control"
                                placeholder="Deskripsi singkat tentang buku ini...">{{ old('description', $product->description ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="admin-card reveal">
                        <div class="admin-card-title">Harga & Stok</div>
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label class="form-label">Harga (Rp) <span style="color:#ef4444">*</span></label>
                                <input type="number" name="price" class="form-control"
                                    value="{{ old('price', $product->price ?? '') }}"
                                    placeholder="85000" min="0" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Stok <span style="color:#ef4444">*</span></label>
                                <input type="number" name="stock" class="form-control"
                                    value="{{ old('stock', $product->stock ?? '') }}"
                                    placeholder="50" min="0" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SIDEBAR -->
                <div>
                    <div class="admin-card reveal" style="margin-bottom:1.5rem;">
                        <div class="admin-card-title">Cover Buku</div>
                        <label for="coverInput">
                            <div class="cover-preview" id="coverPreview">
                                @if(isset($product) && $product->cover)
                                    <img src="{{ Storage::url($product->cover) }}" id="previewImg">
                                @else
                                    <img id="previewImg" style="display:none;">
                                @endif
                                <div class="cover-preview-text" id="previewText" style="{{ isset($product) && $product->cover ? 'display:none' : '' }}">
                                    <div class="cover-preview-icon">📸</div>
                                    <div class="cover-preview-label">Klik untuk upload<br>cover buku</div>
                                </div>
                            </div>
                        </label>
                        <input type="file" name="cover" id="coverInput" accept="image/*" style="display:none;">
                        <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.5rem;text-align:center;">JPG, PNG. Maks 2MB</p>
                    </div>

                    <div class="admin-card reveal">
                        <div class="admin-card-title">Aksi</div>
                        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-bottom:0.75rem;">
                            <i class="fas fa-save"></i> {{ isset($product) ? 'Simpan Perubahan' : 'Tambah Produk' }}
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline" style="width:100%;justify-content:center;">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </div>
        </form>
    @endsection

@push('scripts')
<script>
    document.getElementById('coverInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('previewImg');
            img.src = e.target.result;
            img.style.display = 'block';
            document.getElementById('previewText').style.display = 'none';
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush