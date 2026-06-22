@extends('layouts.admin')
@section('title', isset($article) ? 'Edit Artikel' : 'Tulis Artikel')

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
    .form-layout{display:grid;grid-template-columns:1fr 300px;gap:1.5rem;}
    .admin-card{background:white;border-radius:16px;padding:1.5rem;box-shadow:var(--shadow);margin-bottom:1.5rem;}
    .admin-card-title{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:var(--brown);margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:2px solid var(--cream-dark);}
    textarea.form-control{resize:vertical;}
    .editor-toolbar{display:flex;gap:0.5rem;padding:0.75rem;background:var(--cream);border-radius:8px 8px 0 0;border:1.5px solid var(--cream-dark);border-bottom:none;flex-wrap:wrap;}
    .editor-btn{padding:0.35rem 0.75rem;background:white;border:1px solid var(--cream-dark);border-radius:6px;font-size:0.8rem;cursor:pointer;transition:var(--transition);font-family:'Plus Jakarta Sans',sans-serif;color:var(--brown);}
    .editor-btn:hover{background:var(--orange);color:white;border-color:var(--orange);}
    #bodyEditor{border-radius:0 0 8px 8px;min-height:350px;font-size:0.9rem;line-height:1.8;}
    .thumbnail-preview{width:100%;height:160px;border-radius:12px;background:var(--cream);border:2px dashed var(--cream-dark);display:flex;flex-direction:column;align-items:center;justify-content:center;cursor:pointer;transition:var(--transition);overflow:hidden;position:relative;}
    .thumbnail-preview:hover{border-color:var(--orange);}
    .thumbnail-preview img{width:100%;height:100%;object-fit:cover;position:absolute;}
    select.form-control{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%238B7355' d='M6 8L1 3h10z'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 1rem center;padding-right:2.5rem;}
    @media(max-width:768px){
        .admin-wrapper{grid-template-columns:1fr;}
        .admin-sidebar{display:none;}
        .form-layout{grid-template-columns:1fr;}
    }
</style>
@endpush

@section('content')
<div>
        <div class="admin-page-title">{{ isset($article) ? 'Edit Artikel' : 'Tulis Artikel' }}</div>
        <div class="admin-page-sub">{{ isset($article) ? 'Perbarui konten artikel.' : 'Buat artikel baru untuk blog.' }}</div>

        @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:1.5rem;">
            <i class="fas fa-exclamation-circle"></i>
            <ul style="margin:0;padding-left:1rem;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form method="POST"
            action="{{ isset($article) ? route('admin.articles.update', $article) : route('admin.articles.store') }}"
            enctype="multipart/form-data">
            @csrf
            @if(isset($article)) @method('PUT') @endif
            <input type="hidden" name="body" id="bodyInput">

            <div class="form-layout">
                <!-- MAIN -->
                <div>
                    <div class="admin-card reveal">
                        <div class="admin-card-title">Konten Artikel</div>

                        <div class="form-group">
                            <label class="form-label">Judul Artikel <span style="color:#ef4444">*</span></label>
                            <input type="text" name="title" class="form-control"
                                value="{{ old('title', $article->title ?? '') }}"
                                placeholder="Judul artikel yang menarik..." required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Ringkasan (Excerpt)</label>
                            <textarea name="excerpt" class="form-control" rows="3"
                                placeholder="Ringkasan singkat artikel untuk preview...">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Isi Artikel <span style="color:#ef4444">*</span></label>
                            <div class="editor-toolbar">
                                <button type="button" class="editor-btn" onclick="fmt('bold')"><i class="fas fa-bold"></i></button>
                                <button type="button" class="editor-btn" onclick="fmt('italic')"><i class="fas fa-italic"></i></button>
                                <button type="button" class="editor-btn" onclick="fmt('underline')"><i class="fas fa-underline"></i></button>
                                <button type="button" class="editor-btn" onclick="fmtBlock('h2')">H2</button>
                                <button type="button" class="editor-btn" onclick="fmtBlock('h3')">H3</button>
                                <button type="button" class="editor-btn" onclick="fmtBlock('p')"><i class="fas fa-paragraph"></i></button>
                                <button type="button" class="editor-btn" onclick="fmt('insertUnorderedList')"><i class="fas fa-list-ul"></i></button>
                                <button type="button" class="editor-btn" onclick="fmt('insertOrderedList')"><i class="fas fa-list-ol"></i></button>
                                <button type="button" class="editor-btn" onclick="fmtBlock('blockquote')"><i class="fas fa-quote-right"></i></button>
                            </div>
                            <div id="bodyEditor" class="form-control" contenteditable="true"
                                style="border-radius:0 0 8px 8px;min-height:350px;outline:none;overflow-y:auto;">
                                {!! old('body', $article->body ?? '') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SIDEBAR -->
                <div>
                    <div class="admin-card reveal">
                        <div class="admin-card-title">Publikasi</div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="draft" {{ old('status', $article->status ?? '') === 'draft' ? 'selected' : '' }}>📝 Draft</option>
                                <option value="published" {{ old('status', $article->status ?? '') === 'published' ? 'selected' : '' }}>✅ Published</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-bottom:0.75rem;">
                            <i class="fas fa-save"></i> {{ isset($article) ? 'Simpan Perubahan' : 'Publikasikan' }}
                        </button>
                        <a href="{{ route('admin.articles.index') }}" class="btn btn-outline" style="width:100%;justify-content:center;">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>

                    <div class="admin-card reveal">
                        <div class="admin-card-title">Thumbnail</div>
                        <label for="thumbInput">
                            <div class="thumbnail-preview" id="thumbPreview">
                                @if(isset($article) && $article->thumbnail)
                                    <img src="{{ Storage::url($article->thumbnail) }}" id="thumbImg">
                                @else
                                    <img id="thumbImg" style="display:none;">
                                @endif
                                <div id="thumbText" style="{{ isset($article) && $article->thumbnail ? 'display:none' : '' }};text-align:center;color:var(--text-muted);">
                                    <div style="font-size:2rem;margin-bottom:0.5rem;">🖼️</div>
                                    <div style="font-size:0.8rem;font-weight:600;">Upload Thumbnail</div>
                                </div>
                            </div>
                        </label>
                        <input type="file" name="thumbnail" id="thumbInput" accept="image/*" style="display:none;">
                        <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.5rem;text-align:center;">JPG, PNG. Maks 2MB</p>
                    </div>
                </div>
            </div>
        </form>
    @endsection

@push('scripts')
<script>
    function fmt(cmd) { document.execCommand(cmd, false, null); }
    function fmtBlock(tag) { document.execCommand('formatBlock', false, tag); }

    const bodyEditor = document.getElementById('bodyEditor');
    const bodyInput  = document.getElementById('bodyInput');

    // Sync setiap ada perubahan di editor
    function syncBody() {
        bodyInput.value = bodyEditor.innerHTML.trim();
    }

    bodyEditor.addEventListener('input', syncBody);
    bodyEditor.addEventListener('keyup', syncBody);

    // Sync saat halaman load (untuk mode edit)
    syncBody();

    // Validasi & sync saat submit
    document.querySelector('form').addEventListener('submit', function(e) {
        syncBody();

        const content = bodyEditor.innerText.trim();
        if (!content || content === '') {
            e.preventDefault();
            alert('Isi artikel tidak boleh kosong!');
            bodyEditor.focus();
            return;
        }
    });

    // Preview thumbnail
    document.getElementById('thumbInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('thumbImg');
            img.src = e.target.result;
            img.style.display = 'block';
            document.getElementById('thumbText').style.display = 'none';
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush