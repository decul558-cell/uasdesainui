@extends('layouts.admin')
@section('title', 'Live Chat')
@push('styles')
<style>
    .chat-conv-list{display:flex;flex-direction:column;gap:0.75rem;}
    .chat-conv-card{background:white;border-radius:14px;padding:1.1rem 1.4rem;box-shadow:var(--shadow);display:flex;align-items:center;gap:1rem;text-decoration:none;color:inherit;transition:var(--transition);border-left:4px solid transparent;}
    .chat-conv-card:hover{box-shadow:var(--shadow-lg);transform:translateY(-1px);}
    .chat-conv-card.unread{border-left-color:var(--orange);background:#FFFBF7;}
    .chat-conv-avatar{width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,var(--brown),var(--orange));display:flex;align-items:center;justify-content:center;color:white;font-weight:700;flex-shrink:0;}
    .chat-conv-info{flex:1;min-width:0;}
    .chat-conv-name{font-weight:700;color:var(--brown);font-size:0.9rem;}
    .chat-conv-preview{font-size:0.8rem;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:400px;}
    .chat-conv-meta{text-align:right;flex-shrink:0;}
    .chat-conv-time{font-size:0.72rem;color:var(--text-muted);}
    .status-chip{display:inline-flex;align-items:center;gap:0.3rem;font-size:0.68rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:50px;margin-top:0.3rem;}
    .status-chip.bot{background:#EFF6FF;color:#1E40AF;}
    .status-chip.open{background:#FEF3C7;color:#92400E;}
    .status-chip.closed{background:#F0FDF4;color:#166534;}
    .unread-badge{background:var(--orange);color:white;font-size:0.65rem;font-weight:700;width:20px;height:20px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">💬 Live Chat</h1>
    <p class="page-sub">Kelola percakapan dengan pelanggan</p>
</div>

<div class="admin-card">
    @if($conversations->isEmpty())
        <div style="text-align:center;padding:3rem;color:var(--text-muted);">
            <i class="fas fa-comments" style="font-size:2.5rem;opacity:0.3;display:block;margin-bottom:1rem;"></i>
            Belum ada percakapan.
        </div>
    @else
    <div class="chat-conv-list">
        @foreach($conversations as $conv)
        @php $unread = $conv->unreadCountForAdmin(); @endphp
        <a href="{{ route('admin.chats.show', $conv) }}" class="chat-conv-card {{ $unread > 0 ? 'unread' : '' }}">
            <div class="chat-conv-avatar">{{ strtoupper(substr($conv->user->name ?? '?', 0, 1)) }}</div>
            <div class="chat-conv-info">
                <div class="chat-conv-name">{{ $conv->user->name ?? 'User' }}</div>
                <div class="chat-conv-preview">{{ $conv->lastMessage->message ?? 'Belum ada pesan' }}</div>
            </div>
            @if($unread > 0)<div class="unread-badge">{{ $unread }}</div>@endif
            <div class="chat-conv-meta">
                <div class="chat-conv-time">{{ $conv->last_message_at?->diffForHumans() ?? '-' }}</div>
                <span class="status-chip {{ $conv->status }}">
                    @if($conv->status === 'bot') 🤖 Bot
                    @elseif($conv->status === 'open') 🔴 Perlu Respon
                    @else ✅ Selesai
                    @endif
                </span>
            </div>
        </a>
        @endforeach
    </div>
    <div style="margin-top:1.5rem;">{{ $conversations->links() }}</div>
    @endif
</div>
@endsection