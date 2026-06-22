@extends('layouts.admin')
@section('title', 'Kelola Kupon')

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
    .admin-grid{display:grid;grid-template-columns:1fr 360px;gap:1.5rem;}
    .admin-card{background:white;border-radius:16px;padding:1.5rem;box-shadow:var(--shadow);}
    .admin-card-title{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:var(--brown);margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:2px solid var(--cream-dark);}
    .table{width:100%;border-collapse:collapse;}
    .table th{text-align:left;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--text-muted);padding:0.6rem 1rem;border-bottom:2px solid var(--cream-dark);}
    .table td{padding:1rem;border-bottom:1px solid var(--cream-dark);font-size:0.875rem;vertical-align:middle;}
    .table tr:last-child td{border-bottom:none;}
    .table tr:hover td{background:var(--cream);}
    .badge-active{background:rgba(34,197,94,0.1);color:#16a34a;padding:0.2rem 0.6rem;border-radius:50px;font-size:0.75rem;font-weight:700;}
    .badge-inactive{background:rgba(239,68,68,0.1);color:#ef4444;padding:0.2rem 0.6rem;border-radius:50px;font-size:0.75rem;font-weight:700;}
    .btn-edit{display:inline-flex;align-items:center;gap:0.4rem;padding:0.4rem 0.9rem;background:rgba(59,130,246,0.1);color:#3B82F6;border-radius:8px;font-size:0.8rem;font-weight:700;text-decoration:none;transition:var(--transition);border:none;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;}
    .btn-edit:hover{background:#3B82F6;color:white;}
    .btn-del{display:inline-flex;align-items:center;gap:0.4rem;padding:0.4rem 0.9rem;background:rgba(239,68,68,0.1);color:#ef4444;border-radius:8px;font-size:0.8rem;font-weight:700;border:none;cursor:pointer;transition:var(--transition);font-family:'Plus Jakarta Sans',sans-serif;}
    .btn-del:hover{background:#ef4444;color:white;}
    @media(max-width:768px){.admin-wrapper{grid-template-columns:1fr;}.admin-sidebar{display:none;}.admin-grid{grid-template-columns:1fr;}}
</style>
@endpush

@section('content')
<div>
        <div class="admin-page-title">Kelola Kupon</div>
        <div class="admin-page-sub">Buat dan kelola kupon diskon.</div>

        @if(session('success'))
        <div style="background:rgba(34,197,94,0.1);border:1px solid #16a34a;color:#16a34a;padding:0.75rem 1rem;border-radius:10px;margin-bottom:1rem;">
            {{ session('success') }}
        </div>
        @endif

        <div class="admin-grid">
            <!-- LIST -->
            <div class="admin-card">
                <div class="admin-card-title">Daftar Kupon</div>
                @if($coupons->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Tipe</th>
                            <th>Nilai</th>
                            <th>Min. Order</th>
                            <th>Terpakai</th>
                            <th>Expired</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($coupons as $coupon)
                        <tr>
                            <td style="font-weight:700;color:var(--brown);font-family:monospace;">{{ $coupon->code }}</td>
                            <td>{{ $coupon->type === 'percent' ? 'Persen' : 'Nominal' }}</td>
                            <td style="color:var(--orange);font-weight:700;">
                                {{ $coupon->type === 'percent' ? $coupon->value.'%' : 'Rp '.number_format($coupon->value,0,',','.') }}
                            </td>
                            <td>Rp {{ number_format($coupon->min_order,0,',','.') }}</td>
                            <td>{{ $coupon->used_count }}{{ $coupon->max_uses ? '/'.$coupon->max_uses : '' }}</td>
                            <td style="font-size:0.8rem;color:var(--text-muted);">{{ $coupon->expires_at ? $coupon->expires_at->format('d M Y') : '-' }}</td>
                            <td>
                                @if($coupon->isValid())
                                <span class="badge-active">Aktif</span>
                                @else
                                <span class="badge-inactive">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;gap:0.5rem;">
                                    <button class="btn-edit" onclick="editCoupon({{ $coupon->id }}, '{{ $coupon->code }}', '{{ $coupon->type }}', {{ $coupon->value }}, {{ $coupon->min_order }}, '{{ $coupon->max_uses }}', '{{ $coupon->expires_at?->format('Y-m-d') }}', {{ $coupon->is_active ? 'true' : 'false' }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-del" onclick="return confirm('Hapus kupon ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div style="text-align:center;padding:3rem;color:var(--text-muted);">
                    <i class="fas fa-ticket-alt" style="font-size:3rem;margin-bottom:1rem;opacity:0.3;display:block;"></i>
                    <p>Belum ada kupon.</p>
                </div>
                @endif
            </div>

            <!-- FORM -->
            <div>
                <div class="admin-card" style="margin-bottom:1.5rem;">
                    <div class="admin-card-title">Tambah Kupon</div>
                    <form method="POST" action="{{ route('admin.coupons.store') }}">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Kode Kupon</label>
                            <input type="text" name="code" class="form-control" placeholder="Contoh: DISKON10" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipe Diskon</label>
                            <select name="type" class="form-control">
                                <option value="percent">Persen (%)</option>
                                <option value="fixed">Nominal (Rp)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nilai Diskon</label>
                            <input type="number" name="value" class="form-control" placeholder="Contoh: 10" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Minimum Order (Rp)</label>
                            <input type="number" name="min_order" class="form-control" placeholder="0" value="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Maks. Penggunaan</label>
                            <input type="number" name="max_uses" class="form-control" placeholder="Kosongkan = unlimited">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Kadaluarsa</label>
                            <input type="date" name="expires_at" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                            <i class="fas fa-plus"></i> Tambah Kupon
                        </button>
                    </form>
                </div>

                <!-- EDIT FORM -->
                <div class="admin-card" id="editForm" style="display:none;">
                    <div class="admin-card-title">Edit Kupon</div>
                    <form method="POST" id="editFormAction">
                        @csrf @method('PUT')
                        <div class="form-group">
                            <label class="form-label">Kode Kupon</label>
                            <input type="text" name="code" id="editCode" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipe Diskon</label>
                            <select name="type" id="editType" class="form-control">
                                <option value="percent">Persen (%)</option>
                                <option value="fixed">Nominal (Rp)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nilai Diskon</label>
                            <input type="number" name="value" id="editValue" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Minimum Order (Rp)</label>
                            <input type="number" name="min_order" id="editMinOrder" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Maks. Penggunaan</label>
                            <input type="number" name="max_uses" id="editMaxUses" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Kadaluarsa</label>
                            <input type="date" name="expires_at" id="editExpiresAt" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="is_active" id="editIsActive" class="form-control">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                        <div style="display:flex;gap:0.75rem;">
                            <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <button type="button" class="btn btn-outline" onclick="cancelEdit()">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

@push('scripts')
<script>
function editCoupon(id, code, type, value, minOrder, maxUses, expiresAt, isActive) {
    document.getElementById('editForm').style.display = 'block';
    document.getElementById('editCode').value = code;
    document.getElementById('editType').value = type;
    document.getElementById('editValue').value = value;
    document.getElementById('editMinOrder').value = minOrder;
    document.getElementById('editMaxUses').value = maxUses || '';
    document.getElementById('editExpiresAt').value = expiresAt || '';
    document.getElementById('editIsActive').value = isActive ? '1' : '0';
    document.getElementById('editFormAction').action = '/admin/coupons/' + id;
    document.getElementById('editForm').scrollIntoView({behavior:'smooth'});
}
function cancelEdit() {
    document.getElementById('editForm').style.display = 'none';
}
</script>
@endpush
