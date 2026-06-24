@extends('layouts.admin')
@section('title', 'Pre-Order')
@push('styles')
<style>
    .preorder-group{margin-bottom:1.5rem;}
    .preorder-group-header{display:flex;align-items:center;justify-content:space-between;background:white;border-radius:14px 14px 0 0;padding:1.1rem 1.4rem;box-shadow:var(--shadow);}
    .preorder-group-info{display:flex;align-items:center;gap:1rem;}
    .preorder-group-cover{width:44px;height:58px;border-radius:8px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:1.2rem;overflow:hidden;background:linear-gradient(135deg,var(--brown),var(--orange));}
    .preorder-group-cover img{width:100%;height:100%;object-fit:cover;}
    .preorder-group-title{font-weight:700;color:var(--brown);font-size:0.92rem;}
    .preorder-group-meta{font-size:0.78rem;color:var(--text-muted);margin-top:0.15rem;}
    .preorder-table-wrap{background:white;border-radius:0 0 14px 14px;box-shadow:var(--shadow);overflow:hidden;}
    .status-badge{display:inline-flex;align-items:center;gap:0.3rem;padding:0.2rem 0.65rem;border-radius:50px;font-size:0.7rem;font-weight:700;}
    .status-waiting{background:#FEF3C7;color:#92400E;}
    .status-ready{background:#ECFDF5;color:#065F46;}
    .status-cancelled{background:#FEF2F2;color:#991B1B;}
    .stock-info{font-size:0.78rem;color:var(--text-muted);}
    .stock-info.available{color:#065F46;font-weight:700;}
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">⏳ Pre-Order</h1>
    <p class="page-sub">Kelola permintaan pre-order dari pelanggan</p>
</div>

@if($preorders->isEmpty())
<div class="admin-card" style="text-align:center;padding:3rem;color:var(--text-muted);">
    <i class="fas fa-clock" style="font-size:2.5rem;opacity:0.3;display:block;margin-bottom:1rem;"></i>
    Belum ada pre-order.
</div>
@else
    @php $grouped = $preorders->groupBy('product_id'); @endphp
    @foreach($grouped as $productId => $group)
    @php $product = $group->first()->product; @endphp
    <div class="preorder-group">
        <div class="preorder-group-header">
            <div class="preorder-group-info">
                <div class="preorder-group-cover">
                    @if($product->cover)
                        <img src="{{ Storage::url($product->cover) }}" alt="{{ $product->title }}">
                    @else
                        📖
                    @endif
                </div>
                <div>
                    <div class="preorder-group-title">{{ $product->title }}</div>
                    <div class="preorder-group-meta">
                        {{ $group->count() }} pre-order ·
                        <span class="stock-info {{ $product->stock > 0 ? 'available' : '' }}">
                            Stok saat ini: {{ $product->stock }}
                        </span>
                    </div>
                </div>
            </div>
            @if($product->stock > 0 && $group->where('status', 'waiting')->count() > 0)
            <form method="POST" action="{{ route('admin.preorders.notify', $product->id) }}">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Kirim notifikasi stok tersedia ke semua yang waiting?')">
                    <i class="fas fa-bell"></i> Notifikasi Semua
                </button>
            </form>
            @endif
        </div>
        <div class="preorder-table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Pelanggan</th>
                        <th>Jumlah</th>
                        <th>Catatan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($group as $preorder)
                    <tr>
                        <td>
                            <div style="font-weight:600;color:var(--brown);">{{ $preorder->user->name ?? '-' }}</div>
                            <div style="font-size:0.75rem;color:var(--text-muted);">{{ $preorder->user->email ?? '-' }}</div>
                        </td>
                        <td>{{ $preorder->quantity }}</td>
                        <td style="max-width:200px;">
                            @if($preorder->note)
                                <span style="font-size:0.82rem;color:var(--text-muted);font-style:italic;">"{{ \Illuminate\Support\Str::limit($preorder->note, 40) }}"</span>
                            @else
                                <span style="color:var(--text-muted);">-</span>
                            @endif
                        </td>
                        <td>{{ $preorder->created_at->format('d M Y') }}</td>
                        <td>
                            @php
                                $sl = ['waiting' => '⏳ Menunggu', 'ready' => '✅ Siap', 'cancelled' => '❌ Batal'];
                                $sc = ['waiting' => 'status-waiting', 'ready' => 'status-ready', 'cancelled' => 'status-cancelled'];
                            @endphp
                            <span class="status-badge {{ $sc[$preorder->status] ?? '' }}">{{ $sl[$preorder->status] ?? $preorder->status }}</span>
                        </td>
                        <td>
                            @if($preorder->status === 'waiting')
                            <form method="POST" action="{{ route('admin.preorders.cancel', $preorder->id) }}">
                                @csrf
                                <button type="submit" class="btn-del btn-sm" onclick="return confirm('Batalkan pre-order ini?')">
                                    <i class="fas fa-times"></i> Batal
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach

    <div style="margin-top:1.5rem;">{{ $preorders->links() }}</div>
@endif
@endsection