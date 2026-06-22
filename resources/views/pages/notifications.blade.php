@extends('layouts.app')
@section('title', 'Notifikasi')
@push('styles')
<style>
    .page-header{background: linear-gradient(135deg,#3D2645 0%,#5B4B8A 100%);padding:3rem 2rem;color:white;text-align:center;}
    .page-header-title{font-family:'Playfair Display',serif;font-size:clamp(2rem,4vw,3rem);font-weight:900;margin-bottom:0.5rem;}
    .notif-layout{max-width:800px;margin:0 auto;padding:3rem 0;}
    .notif-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;}
    .notif-list{display:flex;flex-direction:column;gap:1rem;}
    .notif-item{background:white;border-radius:16px;padding:1.25rem 1.5rem;box-shadow:var(--shadow);display:flex;align-items:flex-start;gap:1rem;transition:var(--transition);border-left:4px solid transparent;}
    .notif-item.unread{border-left-color:var(--gold);background:#FDF8F0;}
    .notif-item:hover{box-shadow:var(--shadow-lg);}
    .notif-icon{width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;}
    .notif-icon-info{background:rgba(91,75,138,0.12);color:var(--indigo);}
    .notif-icon-success{background:#e0f0e6;color:#1d6b3f;}
    .notif-icon-warning{background:rgba(212,162,78,0.18);color:#a8782e;}
    .notif-icon-error{background:rgba(168,51,51,0.12);color:#a83333;}
    .notif-body{flex:1;}
    .notif-title{font-weight:700;color:var(--plum);font-size:0.9rem;margin-bottom:0.25rem;}
    .notif-message{font-size:0.85rem;color:var(--text-muted);line-height:1.6;}
    .notif-time{font-size:0.75rem;color:var(--text-muted);margin-top:0.35rem;}
    .notif-unread-dot{width:8px;height:8px;background:var(--gold);border-radius:50%;flex-shrink:0;margin-top:0.35rem;}
    .empty-state{text-align:center;padding:5rem 2rem;}
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="page-header-title">Notifikasi</h1>
        <p style="opacity:0.8;font-size:0.95rem;">Informasi terbaru untuk kamu</p>
    </div>
</div>

<div class="container">
    <div class="notif-layout">
        @if($notifications->count())
        <div class="notif-header reveal">
            <p style="font-size:0.875rem;color:var(--text-muted);">
                <strong style="color:var(--plum);">{{ $notifications->total() }}</strong> notifikasi
            </p>
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf
                <button type="submit" class="btn btn-outline btn-sm">
                    <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                </button>
            </form>
        </div>

        <div class="notif-list">
            @foreach($notifications as $notif)
            @php
                $icons = ['info'=>'fa-info-circle','success'=>'fa-check-circle','warning'=>'fa-exclamation-circle','error'=>'fa-times-circle'];
                $iconClass = 'notif-icon-' . $notif->type;
                $icon = $icons[$notif->type] ?? 'fa-bell';
            @endphp
            <div class="notif-item {{ !$notif->is_read ? 'unread' : '' }} reveal">
                <div class="notif-icon {{ $iconClass }}">
                    <i class="fas {{ $icon }}"></i>
                </div>
                <div class="notif-body">
                    <div class="notif-title">{{ $notif->title }}</div>
                    <div class="notif-message">{{ $notif->message }}</div>
                    <div class="notif-time"><i class="fas fa-clock"></i> {{ $notif->created_at->diffForHumans() }}</div>
                </div>
                @if(!$notif->is_read)
                <div class="notif-unread-dot"></div>
                <form method="POST" action="{{ route('notifications.read', $notif->id) }}">
                    @csrf
                    <button type="submit" style="background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:0.75rem;white-space:nowrap;" title="Tandai dibaca">
                        <i class="fas fa-check"></i>
                    </button>
                </form>
                @endif
            </div>
            @endforeach
        </div>

        <div style="margin-top:2rem;">{{ $notifications->links() }}</div>

        @else
        <div class="empty-state reveal">
            <div style="font-size:5rem;margin-bottom:1rem;">🔔</div>
            <h2 style="font-family:'Playfair Display',serif;font-size:1.8rem;color:var(--plum);margin-bottom:0.75rem;">Tidak Ada Notifikasi</h2>
            <p style="color:var(--text-muted);">Kamu sudah up to date!</p>
        </div>
        @endif
    </div>
</div>
@endsection