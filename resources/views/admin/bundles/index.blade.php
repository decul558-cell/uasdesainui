@extends('layouts.admin')
@section('title', 'Kelola Bundle')
@section('content')
<div class="page-header">
    <div style="display:flex;align-items:center;justify-content:space-between;">
        <div>
            <h1 class="page-title">Bundle Buku</h1>
            <p class="page-sub">Kelola paket bundling buku dengan harga spesial</p>
        </div>
        <a href="{{ route('admin.bundles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Buat Bundle
        </a>
    </div>
</div>

<div class="admin-card">
    <table class="table">
        <thead>
            <tr>
                <th>Bundle</th>
                <th>Jumlah Buku</th>
                <th>Harga Normal</th>
                <th>Harga Bundle</th>
                <th>Diskon</th>
                <th>Stok</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bundles as $bundle)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        @if($bundle->image)
                            <img src="{{ Storage::url($bundle->image) }}" style="width:48px;height:48px;object-fit:cover;border-radius:8px;">
                        @else
                            <div style="width:48px;height:48px;border-radius:8px;background:var(--cream-dark);display:flex;align-items:center;justify-content:center;font-size:1.2rem;">📦</div>
                        @endif
                        <div>
                            <div style="font-weight:600;color:var(--brown);">{{ $bundle->name }}</div>
                            <div style="font-size:0.75rem;color:var(--text-muted);">/bundling/{{ $bundle->slug }}</div>
                        </div>
                    </div>
                </td>
                <td>{{ $bundle->items_count }} buku</td>
                <td style="text-decoration:line-through;color:var(--text-muted);">Rp {{ number_format($bundle->original_price, 0, ',', '.') }}</td>
                <td style="font-weight:700;color:var(--orange);">Rp {{ number_format($bundle->bundle_price, 0, ',', '.') }}</td>
                <td><span style="background:#ECFDF5;color:#065F46;padding:0.2rem 0.6rem;border-radius:50px;font-size:0.75rem;font-weight:700;">{{ $bundle->discount_percent }}% OFF</span></td>
                <td>{{ $bundle->stock }}</td>
                <td>
                    @if($bundle->is_active)
                        <span class="status-paid">Aktif</span>
                    @else
                        <span class="status-cancelled">Nonaktif</span>
                    @endif
                </td>
                <td>
                    <div style="display:flex;gap:0.5rem;">
                        <a href="{{ route('admin.bundles.edit', $bundle) }}" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                        <form method="POST" action="{{ route('admin.bundles.destroy', $bundle) }}" onsubmit="return confirm('Hapus bundle ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-del"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;padding:3rem;color:var(--text-muted);">
                    <i class="fas fa-box-open" style="font-size:2rem;margin-bottom:0.5rem;display:block;"></i>
                    Belum ada bundle. <a href="{{ route('admin.bundles.create') }}">Buat sekarang</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:1rem;">{{ $bundles->links() }}</div>
</div>
@endsection