@extends('layouts.admin')
@section('title', 'Kelola Review')

@section('content')
<div class="page-header">
    <div class="page-title">Kelola Review</div>
    <div class="page-sub">Moderasi ulasan dari pengguna.</div>
</div>

<div class="admin-card reveal">
    <div class="admin-card-title">
        Semua Review
        <span style="font-size:0.8rem;color:var(--text-muted);font-family:'Plus Jakarta Sans',sans-serif;">{{ $reviews->total() }} review</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>Buku</th>
                    <th>Rating</th>
                    <th>Komentar</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td>
                        <div style="font-weight:700;color:var(--brown);font-size:0.875rem;">{{ $review->user->name }}</div>
                        <div style="font-size:0.75rem;color:var(--text-muted);">{{ $review->user->email }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600;color:var(--brown);font-size:0.85rem;max-width:150px;">{{ Str::limit($review->product->title, 30) }}</div>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:0.25rem;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star" style="font-size:0.75rem;color:{{ $i <= $review->rating ? '#F59E0B' : '#E2EAF0' }}"></i>
                            @endfor
                            <span style="font-size:0.8rem;font-weight:700;color:var(--brown);margin-left:0.25rem;">{{ $review->rating }}/5</span>
                        </div>
                    </td>
                    <td style="max-width:200px;">
                        <p style="font-size:0.8rem;color:var(--text-muted);line-height:1.5;">{{ Str::limit($review->comment, 80) }}</p>
                    </td>
                    <td>
                        @if($review->is_approved)
                            <span style="background:#ECFDF5;color:#065F46;padding:0.2rem 0.6rem;border-radius:50px;font-size:0.7rem;font-weight:700;">✅ Disetujui</span>
                        @else
                            <span style="background:#FEF3C7;color:#92400E;padding:0.2rem 0.6rem;border-radius:50px;font-size:0.7rem;font-weight:700;">⏳ Pending</span>
                        @endif
                    </td>
                    <td style="color:var(--text-muted);font-size:0.8rem;white-space:nowrap;">{{ $review->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                            @if(!$review->is_approved)
                            <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">
                                @csrf
                                <button type="submit" class="btn-edit"><i class="fas fa-check"></i> Setujui</button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('admin.reviews.reject', $review) }}">
                                @csrf
                                <button type="submit" class="btn-del"><i class="fas fa-times"></i> Tolak</button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-del" onclick="return confirm('Hapus review ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:3rem;color:var(--text-muted);">
                        <div style="font-size:2.5rem;margin-bottom:0.75rem;">⭐</div>
                        Belum ada review dari pengguna.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $reviews->links() }}</div>
</div>
@endsection