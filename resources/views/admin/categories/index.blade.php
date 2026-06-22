@extends('layouts.admin')
@section('title', 'Kelola Kategori')
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
    .btn-edit{display:inline-flex;align-items:center;gap:0.4rem;padding:0.4rem 0.9rem;background:rgba(59,130,246,0.1);color:#3B82F6;border-radius:8px;font-size:0.8rem;font-weight:700;text-decoration:none;transition:var(--transition);border:none;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;}
    .btn-edit:hover{background:#3B82F6;color:white;}
    .btn-del{display:inline-flex;align-items:center;gap:0.4rem;padding:0.4rem 0.9rem;background:rgba(239,68,68,0.1);color:#ef4444;border-radius:8px;font-size:0.8rem;font-weight:700;border:none;cursor:pointer;transition:var(--transition);font-family:'Plus Jakarta Sans',sans-serif;}
    .btn-del:hover{background:#ef4444;color:white;}
    .action-btns{display:flex;gap:0.5rem;}
    @media(max-width:768px){
        .admin-wrapper{grid-template-columns:1fr;}
        .admin-sidebar{display:none;}
        .admin-grid{grid-template-columns:1fr;}
    }
</style>
@endpush

@section('content')
<div>
        <div class="admin-page-title">Kelola Kategori</div>
        <div class="admin-page-sub">Tambah dan kelola kategori buku.</div>

        <div class="admin-grid">
            <!-- LIST -->
            <div class="admin-card reveal">
                <div class="admin-card-title">Daftar Kategori</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Slug</th>
                            <th>Produk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $i => $cat)
                        <tr>
                            <td style="color:var(--text-muted);">{{ $i + 1 }}</td>
                            <td style="font-weight:700;color:var(--brown);">{{ $cat->name }}</td>
                            <td style="color:var(--text-muted);font-size:0.8rem;">{{ $cat->slug }}</td>
                            <td>
                                <span style="background:rgba(74,144,184,0.1);color:#4A90B8;padding:0.2rem 0.6rem;border-radius:50px;font-size:0.75rem;font-weight:700;">
                                    {{ $cat->products_count }} buku
                                </span>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <button class="btn-edit" onclick="editCategory({{ $cat->id }}, '{{ $cat->name }}', '{{ $cat->description }}')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    @if($cat->products_count === 0)
                                    <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-del" onclick="return confirm('Hapus kategori ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- FORM -->
            <div>
                <!-- ADD FORM -->
                <div class="admin-card reveal" style="margin-bottom:1.5rem;">
                    <div class="admin-card-title">Tambah Kategori</div>
                    <form method="POST" action="{{ route('admin.categories.store') }}">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama kategori..." required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi singkat..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                            <i class="fas fa-plus"></i> Tambah Kategori
                        </button>
                    </form>
                </div>

                <!-- EDIT FORM -->
                <div class="admin-card reveal" id="editForm" style="display:none;">
                    <div class="admin-card-title">Edit Kategori</div>
                    <form method="POST" id="editFormAction">
                        @csrf @method('PUT')
                        <div class="form-group">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" name="name" id="editName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" id="editDesc" class="form-control" rows="3"></textarea>
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
    function editCategory(id, name, desc) {
        document.getElementById('editForm').style.display = 'block';
        document.getElementById('editName').value = name;
        document.getElementById('editDesc').value = desc;
        document.getElementById('editFormAction').action = '/admin/categories/' + id;
        document.getElementById('editForm').scrollIntoView({behavior:'smooth'});
    }
    function cancelEdit() {
        document.getElementById('editForm').style.display = 'none';
    }
</script>
@endpush