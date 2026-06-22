@extends('layouts.admin')
@section('title', 'Kelola Pengumuman')

@section('content')
<div class="page-header">
    <div class="page-title">Kelola Pengumuman</div>
    <div class="page-sub">Buat pengumuman yang tampil di semua halaman.</div>
</div>

<div style="display:grid;grid-template-columns:1fr 380px;gap:1.5rem;">
    <!-- LIST -->
    <div>
        @if($announcements->count())
        <div style="display:flex;flex-direction:column;gap:1rem;">
            @foreach($announcements as $ann)
            @php
                $colors = ['info'=>'#4A90B8','warning'=>'#F59E0B','success'=>'#10B981','danger'=>'#ef4444'];
                $bgColors = ['info'=>'rgba(74,144,184,0.1)','warning'=>'rgba(245,158,11,0.1)','success'=>'rgba(16,185,129,0.1)','danger'=>'rgba(239,68,68,0.1)'];
            @endphp
            <div class="admin-card reveal" style="border-left:4px solid {{ $colors[$ann->type] ?? '#4A90B8' }};margin-bottom:0;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
                    <div style="flex:1;">
                        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.5rem;flex-wrap:wrap;">
                            <span style="font-weight:700;color:var(--brown);">{{ $ann->title }}</span>
                            <span style="background:{{ $bgColors[$ann->type] ?? '' }};color:{{ $colors[$ann->type] ?? '' }};padding:0.2rem 0.6rem;border-radius:50px;font-size:0.7rem;font-weight:700;text-transform:uppercase;">{{ $ann->type }}</span>
                            @if($ann->is_active)
                            <span style="background:#ECFDF5;color:#065F46;padding:0.2rem 0.6rem;border-radius:50px;font-size:0.7rem;font-weight:700;">Aktif</span>
                            @else
                            <span style="background:#F3F4F6;color:#6B7280;padding:0.2rem 0.6rem;border-radius:50px;font-size:0.7rem;font-weight:700;">Nonaktif</span>
                            @endif
                        </div>
                        <p style="font-size:0.875rem;color:var(--text-muted);line-height:1.6;">{{ $ann->message }}</p>
                        @if($ann->end_at)
                        <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.5rem;"><i class="fas fa-clock"></i> Berakhir: {{ $ann->end_at->format('d M Y H:i') }}</p>
                        @endif
                    </div>
                    <div style="display:flex;gap:0.5rem;flex-shrink:0;">
                        <form method="POST" action="{{ route('admin.announcements.toggle', $ann) }}">
                            @csrf
                            <button type="submit" class="{{ $ann->is_active ? 'btn-del' : 'btn-edit' }}" style="white-space:nowrap;">
                                <i class="fas {{ $ann->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                {{ $ann->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.announcements.destroy', $ann) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-del" onclick="return confirm('Hapus pengumuman ini?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="admin-card" style="text-align:center;padding:3rem;">
            <div style="font-size:3rem;margin-bottom:1rem;">📢</div>
            <h3 style="font-family:'Playfair Display',serif;color:var(--brown);margin-bottom:0.5rem;">Belum Ada Pengumuman</h3>
            <p style="color:var(--text-muted);">Buat pengumuman pertamamu di form sebelah.</p>
        </div>
        @endif
    </div>

    <!-- FORM -->
    <div>
        <div class="admin-card reveal">
            <div class="admin-card-title">Buat Pengumuman Baru</div>
            <form method="POST" action="{{ route('admin.announcements.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Judul <span style="color:#ef4444">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="Judul pengumuman..." required>
                </div>
                <div class="form-group">
                    <label class="form-label">Pesan <span style="color:#ef4444">*</span></label>
                    <textarea name="message" class="form-control" rows="3" placeholder="Isi pengumuman..." required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Tipe</label>
                    <select name="type" class="form-control">
                        <option value="info">ℹ️ Info (Biru)</option>
                        <option value="success">✅ Success (Hijau)</option>
                        <option value="warning">⚠️ Warning (Kuning)</option>
                        <option value="danger">❌ Danger (Merah)</option>
                    </select>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Mulai Tampil</label>
                        <input type="datetime-local" name="start_at" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Berakhir</label>
                        <input type="datetime-local" name="end_at" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:0.75rem;cursor:pointer;">
                        <input type="checkbox" name="is_active" checked style="width:16px;height:16px;accent-color:var(--orange);">
                        <span style="font-weight:600;color:var(--brown);font-size:0.875rem;">Aktifkan sekarang</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                    <i class="fas fa-bullhorn"></i> Buat Pengumuman
                </button>
            </form>
        </div>
    </div>
</div>
@endsection