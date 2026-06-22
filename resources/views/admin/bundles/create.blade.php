@extends('layouts.admin')
@section('title', 'Buat Bundle')
@section('content')
<div class="page-header">
    <div style="display:flex;align-items:center;gap:1rem;">
        <a href="{{ route('admin.bundles.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i></a>
        <div>
            <h1 class="page-title">Buat Bundle Baru</h1>
            <p class="page-sub">Buat paket buku dengan harga spesial</p>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.bundles.store') }}" enctype="multipart/form-data">
    @csrf
    <div style="display:grid;grid-template-columns:1fr 360px;gap:1.5rem;align-items:start;">
        
        {{-- LEFT --}}
        <div>
            <div class="admin-card">
                <div class="admin-card-title">Informasi Bundle</div>
                <div class="form-group">
                    <label class="form-label">Nama Bundle *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Contoh: Paket Seri Harry Potter" required>
                    @error('name')<p style="color:#ef4444;font-size:0.8rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Deskripsikan isi bundle dan keuntungannya...">{{ old('description') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Gambar Bundle</label>
                    <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(this)">
                    <img id="imagePreview" src="" style="display:none;margin-top:0.75rem;width:100%;max-height:200px;object-fit:cover;border-radius:8px;">
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-title">
                    Pilih Buku
                    <span style="font-size:0.75rem;font-weight:400;color:var(--text-muted);">Minimal 2 buku</span>
                </div>
                <div id="productList" style="display:flex;flex-direction:column;gap:0.5rem;">
                    @foreach($products as $product)
                    <label style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem;border:1.5px solid var(--cream-dark);border-radius:8px;cursor:pointer;transition:all 0.2s;" 
                           onmouseover="this.style.borderColor='var(--orange)'" onmouseout="if(!this.querySelector('input').checked)this.style.borderColor='var(--cream-dark)'"
                           id="label-{{ $product->id }}">
                        <input type="checkbox" name="products[]" value="{{ $product->id }}" 
                               data-price="{{ $product->price }}" data-name="{{ $product->title }}"
                               onchange="toggleProduct(this, {{ $product->id }})"
                               style="width:16px;height:16px;accent-color:var(--orange);">
                        <div style="flex:1;">
                            <div style="font-weight:600;font-size:0.875rem;">{{ $product->title }}</div>
                            <div style="font-size:0.75rem;color:var(--text-muted);">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        </div>
                        <div id="qty-{{ $product->id }}" style="display:none;align-items:center;gap:0.5rem;">
                            <label style="font-size:0.75rem;color:var(--text-muted);">Qty:</label>
                            <input type="number" name="quantities[]" value="1" min="1" max="10"
                                   style="width:60px;padding:0.3rem 0.5rem;border:1.5px solid var(--cream-dark);border-radius:6px;font-size:0.875rem;"
                                   onchange="recalcOriginal()">
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('products')<p style="color:#ef4444;font-size:0.8rem;margin-top:0.5rem;">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- RIGHT --}}
        <div>
            <div class="admin-card">
                <div class="admin-card-title">Harga & Stok</div>
                <div class="form-group">
                    <label class="form-label">Total Harga Normal</label>
                    <div style="padding:0.75rem 1rem;background:var(--cream);border-radius:var(--radius);font-weight:700;color:var(--text-muted);font-size:0.9rem;" id="originalPriceDisplay">
                        Pilih buku terlebih dahulu
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Harga Bundle *</label>
                    <input type="number" name="bundle_price" id="bundlePrice" class="form-control" 
                           value="{{ old('bundle_price') }}" placeholder="0" min="0" required
                           oninput="recalcDiscount()">
                    @error('bundle_price')<p style="color:#ef4444;font-size:0.8rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
                </div>
                <div style="background:linear-gradient(135deg,rgba(232,98,42,0.1),rgba(212,168,67,0.1));border-radius:var(--radius);padding:1rem;margin-bottom:1rem;text-align:center;">
                    <div style="font-size:0.75rem;color:var(--text-muted);margin-bottom:0.25rem;">Hemat</div>
                    <div style="font-size:1.5rem;font-weight:900;color:var(--orange);" id="discountDisplay">0%</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Stok *</label>
                    <input type="number" name="stock" class="form-control" value="{{ old('stock', 10) }}" min="0" required>
                    @error('stock')<p style="color:#ef4444;font-size:0.8rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:0.75rem;cursor:pointer;">
                        <input type="checkbox" name="is_active" value="1" checked style="width:16px;height:16px;accent-color:var(--orange);">
                        <span class="form-label" style="margin:0;">Aktifkan Bundle</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">
                    <i class="fas fa-save"></i> Simpan Bundle
                </button>
            </div>

            <div class="admin-card" id="selectedSummary" style="display:none;">
                <div class="admin-card-title">Buku Terpilih</div>
                <div id="selectedList" style="display:flex;flex-direction:column;gap:0.5rem;font-size:0.875rem;"></div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
let originalPrice = 0;

function toggleProduct(checkbox, id) {
    const qtyDiv = document.getElementById('qty-' + id);
    const label = document.getElementById('label-' + id);
    if (checkbox.checked) {
        qtyDiv.style.display = 'flex';
        label.style.borderColor = 'var(--orange)';
        label.style.background = 'rgba(232,98,42,0.04)';
    } else {
        qtyDiv.style.display = 'none';
        label.style.borderColor = 'var(--cream-dark)';
        label.style.background = '';
    }
    recalcOriginal();
    updateSelectedSummary();
}

function recalcOriginal() {
    let total = 0;
    document.querySelectorAll('input[name="products[]"]:checked').forEach(cb => {
        const price = parseFloat(cb.dataset.price);
        const label = document.getElementById('label-' + cb.value);
        const qtyInput = label.querySelector('input[name="quantities[]"]');
        const qty = qtyInput ? parseInt(qtyInput.value) || 1 : 1;
        total += price * qty;
    });
    originalPrice = total;
    document.getElementById('originalPriceDisplay').textContent = total > 0
        ? 'Rp ' + total.toLocaleString('id-ID')
        : 'Pilih buku terlebih dahulu';
    recalcDiscount();
}

function recalcDiscount() {
    const bundlePrice = parseFloat(document.getElementById('bundlePrice').value) || 0;
    if (originalPrice > 0 && bundlePrice > 0) {
        const pct = Math.round(((originalPrice - bundlePrice) / originalPrice) * 100);
        document.getElementById('discountDisplay').textContent = pct + '% OFF';
    } else {
        document.getElementById('discountDisplay').textContent = '0%';
    }
}

function updateSelectedSummary() {
    const checked = document.querySelectorAll('input[name="products[]"]:checked');
    const summary = document.getElementById('selectedSummary');
    const list = document.getElementById('selectedList');
    if (checked.length === 0) { summary.style.display = 'none'; return; }
    summary.style.display = 'block';
    list.innerHTML = '';
    checked.forEach(cb => {
        const div = document.createElement('div');
        div.style = 'display:flex;justify-content:space-between;padding:0.4rem 0;border-bottom:1px solid var(--cream-dark);';
        div.innerHTML = `<span>${cb.dataset.name}</span><span style="color:var(--text-muted);">Rp ${parseFloat(cb.dataset.price).toLocaleString('id-ID')}</span>`;
        list.appendChild(div);
    });
}

function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection