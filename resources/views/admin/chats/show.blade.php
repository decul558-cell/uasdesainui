@extends('layouts.admin')
@section('title', 'Chat dengan ' . ($conversation->user->name ?? 'User'))
@push('styles')
<style>
    .chat-admin-card{background:white;border-radius:16px;box-shadow:var(--shadow);overflow:hidden;display:flex;flex-direction:column;height:70vh;}
    .chat-admin-header{padding:1.1rem 1.5rem;border-bottom:1px solid var(--cream-dark);display:flex;align-items:center;justify-content:space-between;}
    .chat-admin-user{display:flex;align-items:center;gap:0.75rem;}
    .chat-admin-avatar{width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--brown),var(--orange));display:flex;align-items:center;justify-content:center;color:white;font-weight:700;flex-shrink:0;}
    .chat-admin-body{flex:1;overflow-y:auto;padding:1.5rem;display:flex;flex-direction:column;gap:0.9rem;background:var(--cream);}
    .chat-bubble-row{display:flex;gap:0.6rem;max-width:75%;}
    .chat-bubble-row.mine{align-self:flex-end;flex-direction:row-reverse;}
    .chat-avatar{width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;flex-shrink:0;color:white;}
    .chat-avatar.bot{background:var(--gold);color:#1A2E3F;}
    .chat-avatar.admin{background:var(--orange);}
    .chat-avatar.user{background:#4A90B8;}
    .chat-bubble{padding:0.7rem 1rem;border-radius:14px;font-size:0.875rem;line-height:1.5;}
    .chat-bubble-row:not(.mine) .chat-bubble{background:white;color:var(--text);border-bottom-left-radius:4px;box-shadow:var(--shadow);}
    .chat-bubble-row.mine .chat-bubble{background:var(--brown);color:white;border-bottom-right-radius:4px;}
    .chat-bubble-meta{font-size:0.65rem;color:var(--text-muted);margin-top:0.3rem;}
    .chat-bubble-row.mine .chat-bubble-meta{text-align:right;}
    .chat-admin-footer{padding:1rem 1.25rem;background:white;border-top:1px solid var(--cream-dark);display:flex;gap:0.75rem;}
    .chat-admin-input{flex:1;border:1.5px solid var(--cream-dark);border-radius:50px;padding:0.7rem 1.2rem;font-family:'Plus Jakarta Sans',sans-serif;font-size:0.875rem;outline:none;}
    .chat-admin-input:focus{border-color:var(--orange);box-shadow:0 0 0 3px rgba(232,98,42,0.1);}
</style>
@endpush

@section('content')
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
    <div>
        <a href="{{ route('admin.chats.index') }}" style="color:var(--text-muted);font-size:0.85rem;text-decoration:none;"><i class="fas fa-arrow-left"></i> Kembali</a>
        <h1 class="page-title" style="margin-top:0.5rem;">💬 {{ $conversation->user->name ?? 'User' }}</h1>
        <p class="page-sub">{{ $conversation->user->email ?? '-' }}</p>
    </div>
    @if($conversation->status !== 'closed')
    <form method="POST" action="{{ route('admin.chats.close', $conversation) }}">
        @csrf
        <button type="submit" class="btn btn-outline btn-sm" onclick="return confirm('Tandai percakapan ini selesai?')">
            <i class="fas fa-check-circle"></i> Tandai Selesai
        </button>
    </form>
    @endif
</div>

<div class="chat-admin-card">
    <div class="chat-admin-header">
        <div class="chat-admin-user">
            <div class="chat-admin-avatar">{{ strtoupper(substr($conversation->user->name ?? '?', 0, 1)) }}</div>
            <div>
                <div style="font-weight:700;color:var(--brown);font-size:0.9rem;">{{ $conversation->user->name ?? 'User' }}</div>
                <div style="font-size:0.75rem;color:var(--text-muted);">
                    Status: <strong>{{ ucfirst($conversation->status) }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="chat-admin-body" id="chatBody">
        @foreach($conversation->messages as $msg)
        @php
            $isMine = $msg->sender_type === 'admin';
            $avatarLabel = $msg->sender_type === 'bot' ? '🤖' : ($msg->sender_type === 'admin' ? 'A' : 'U');
        @endphp
        <div class="chat-bubble-row {{ $isMine ? 'mine' : '' }}">
            <div class="chat-avatar {{ $msg->sender_type }}">{{ $avatarLabel }}</div>
            <div>
                <div class="chat-bubble">{{ $msg->message }}</div>
                <div class="chat-bubble-meta">{{ $msg->created_at->format('d M, H:i') }}</div>
            </div>
        </div>
        @endforeach
    </div>

    @if($conversation->status !== 'closed')
    <form class="chat-admin-footer" id="adminChatForm" method="POST" action="{{ route('admin.chats.reply', $conversation) }}">
        @csrf
        <input type="text" name="message" id="chatInput" class="chat-admin-input" placeholder="Tulis balasan..." autocomplete="off" required>
        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i></button>
    </form>
    @else
    <div class="chat-admin-footer" style="justify-content:center;color:var(--text-muted);font-size:0.85rem;">
        <i class="fas fa-check-circle" style="color:#10B981;"></i> Percakapan ini sudah ditutup.
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    const chatBody = document.getElementById('chatBody');
    chatBody.scrollTop = chatBody.scrollHeight;

    let lastId = {{ $conversation->messages->last()?->id ?? 0 }};

    async function poll() {
        try {
            const res = await fetch(`{{ route('admin.chats.poll', $conversation) }}?after_id=${lastId}`);
            const data = await res.json();
            data.messages.forEach(msg => {
                const isMine = msg.sender_type === 'admin';
                const avatarLabel = msg.sender_type === 'bot' ? '🤖' : (msg.sender_type === 'admin' ? 'A' : 'U');
                const time = new Date(msg.created_at).toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'});
                const div = document.createElement('div');
                div.className = `chat-bubble-row ${isMine ? 'mine' : ''}`;
                div.innerHTML = `
                    <div class="chat-avatar ${msg.sender_type}">${avatarLabel}</div>
                    <div>
                        <div class="chat-bubble"></div>
                        <div class="chat-bubble-meta">${time}</div>
                    </div>
                `;
                div.querySelector('.chat-bubble').textContent = msg.message;
                chatBody.appendChild(div);
                lastId = Math.max(lastId, msg.id);
            });
            if (data.messages.length) chatBody.scrollTop = chatBody.scrollHeight;
        } catch (e) { console.error(e); }
    }

    setInterval(poll, 3000);
</script>
@endpush