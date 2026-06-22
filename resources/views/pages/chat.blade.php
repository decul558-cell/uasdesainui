@extends('layouts.app')
@section('title', 'Live Chat')
@push('styles')
<style>
    .chat-wrap{max-width:760px;margin:0 auto;padding:2.5rem 0;}
    .chat-card{background:white;border-radius:20px;box-shadow:var(--shadow-lg);overflow:hidden;display:flex;flex-direction:column;height:75vh;}
    .chat-header{background:var(--plum-dark);color:white;padding:1.25rem 1.5rem;display:flex;align-items:center;gap:0.75rem;}
    .chat-header-icon{width:42px;height:42px;border-radius:50%;background:var(--gold);display:flex;align-items:center;justify-content:center;color:var(--plum-dark);font-size:1.1rem;flex-shrink:0;}
    .chat-header-title{font-weight:700;font-size:0.95rem;}
    .chat-header-sub{font-size:0.75rem;color:#b39ec0;display:flex;align-items:center;gap:0.4rem;}
    .status-dot{width:8px;height:8px;border-radius:50%;background:#3a8a5c;display:inline-block;}
    .status-dot.escalated{background:var(--gold);}

    .chat-body{flex:1;overflow-y:auto;padding:1.5rem;display:flex;flex-direction:column;gap:0.9rem;background:var(--mist);}
    .chat-bubble-row{display:flex;gap:0.6rem;max-width:80%;}
    .chat-bubble-row.mine{align-self:flex-end;flex-direction:row-reverse;}
    .chat-avatar{width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;flex-shrink:0;color:white;}
    .chat-avatar.bot{background:var(--gold);color:var(--plum-dark);}
    .chat-avatar.admin{background:var(--magenta);}
    .chat-avatar.user{background:var(--indigo);}
    .chat-bubble{padding:0.7rem 1rem;border-radius:14px;font-size:0.875rem;line-height:1.5;}
    .chat-bubble-row:not(.mine) .chat-bubble{background:white;color:var(--text);border-bottom-left-radius:4px;box-shadow:var(--shadow);}
    .chat-bubble-row.mine .chat-bubble{background:var(--plum);color:white;border-bottom-right-radius:4px;}
    .chat-bubble-meta{font-size:0.65rem;color:var(--text-muted);margin-top:0.3rem;}
    .chat-bubble-row.mine .chat-bubble-meta{text-align:right;}

    .chat-footer{padding:1rem 1.25rem;background:white;border-top:1px solid var(--mist-dark);display:flex;gap:0.75rem;}
    .chat-input{flex:1;border:1.5px solid var(--mist-dark);border-radius:50px;padding:0.7rem 1.2rem;font-family:'Plus Jakarta Sans',sans-serif;font-size:0.875rem;outline:none;transition:var(--transition);}
    .chat-input:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(212,162,78,0.15);}
    .chat-send-btn{width:44px;height:44px;border-radius:50%;background:var(--plum);color:white;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:var(--transition);flex-shrink:0;}
    .chat-send-btn:hover{background:var(--gold);color:var(--plum-dark);}
    .chat-send-btn:disabled{opacity:0.5;cursor:not-allowed;}

    .typing-indicator{font-size:0.75rem;color:var(--text-muted);font-style:italic;padding-left:0.5rem;}
    .quick-replies{display:flex;gap:0.5rem;flex-wrap:wrap;padding:0 1.25rem 1rem;}
    .quick-reply-btn{background:white;border:1.5px solid var(--mist-dark);color:var(--plum);font-size:0.78rem;font-weight:600;padding:0.4rem 0.9rem;border-radius:50px;cursor:pointer;transition:var(--transition);}
    .quick-reply-btn:hover{background:var(--gold);border-color:var(--gold);color:var(--plum-dark);}

    @media(max-width:768px){
        .chat-wrap{padding:1rem;}
        .chat-card{height:80vh;}
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="chat-wrap">
        <div class="chat-card">
            <div class="chat-header">
                <div class="chat-header-icon"><i class="fas fa-headset"></i></div>
                <div>
                    <div class="chat-header-title">Bantuan Pustaka Nusantara</div>
                    <div class="chat-header-sub" id="chatStatusLabel">
                        <span class="status-dot" id="statusDot"></span>
                        <span id="statusText">{{ $conversation->status === 'open' ? 'Terhubung dengan Admin' : 'Asisten Virtual' }}</span>
                    </div>
                </div>
            </div>

            <div class="chat-body" id="chatBody">
                @if($messages->isEmpty())
                <div style="text-align:center;color:var(--text-muted);font-size:0.85rem;margin-top:2rem;">
                    👋 Halo! Tanyakan apa saja seputar pesanan, pengiriman, atau pembayaran.
                </div>
                @endif

                @foreach($messages as $msg)
                @include('partials.chat-bubble', ['msg' => $msg])
                @endforeach
            </div>

            @if($conversation->status !== 'closed')
            <div class="quick-replies">
                <button class="quick-reply-btn" onclick="sendQuick('Bagaimana cara pembayaran?')">💳 Cara bayar</button>
                <button class="quick-reply-btn" onclick="sendQuick('Berapa lama pengiriman?')">🚚 Estimasi kirim</button>
                <button class="quick-reply-btn" onclick="sendQuick('Bagaimana cara retur barang?')">↩️ Cara retur</button>
            </div>

            <form class="chat-footer" id="chatForm">
                <input type="text" class="chat-input" id="chatInput" placeholder="Tulis pesan..." autocomplete="off" required>
                <button type="submit" class="chat-send-btn" id="sendBtn"><i class="fas fa-paper-plane"></i></button>
            </form>
            @else
            <div class="chat-footer" style="justify-content:center;color:var(--text-muted);font-size:0.85rem;">
                <i class="fas fa-check-circle" style="color:#3a8a5c;"></i> Percakapan ini sudah ditutup.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const chatBody   = document.getElementById('chatBody');
    const chatForm   = document.getElementById('chatForm');
    const chatInput  = document.getElementById('chatInput');
    const sendBtn    = document.getElementById('sendBtn');
    const statusDot  = document.getElementById('statusDot');
    const statusText = document.getElementById('statusText');

    let lastId = {{ $messages->last()?->id ?? 0 }};
    const csrfToken = '{{ csrf_token() }}';

    function scrollToBottom() {
        chatBody.scrollTop = chatBody.scrollHeight;
    }
    scrollToBottom();

    function bubbleHtml(msg) {
        const isMine = msg.sender_type === 'user';
        const avatarClass = msg.sender_type;
        const avatarLabel = msg.sender_type === 'bot' ? '🤖' : (msg.sender_type === 'admin' ? 'A' : 'U');
        const time = new Date(msg.created_at).toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'});
        return `
            <div class="chat-bubble-row ${isMine ? 'mine' : ''}">
                <div class="chat-avatar ${avatarClass}">${avatarLabel}</div>
                <div>
                    <div class="chat-bubble">${escapeHtml(msg.message)}</div>
                    <div class="chat-bubble-meta">${time}</div>
                </div>
            </div>
        `;
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function appendMessages(messages) {
        messages.forEach(msg => {
            chatBody.insertAdjacentHTML('beforeend', bubbleHtml(msg));
            lastId = Math.max(lastId, msg.id);
        });
        if (messages.length) scrollToBottom();
    }

    function updateStatus(status) {
        if (status === 'open') {
            statusDot.classList.add('escalated');
            statusText.textContent = 'Terhubung dengan Admin';
        } else if (status === 'closed') {
            statusText.textContent = 'Percakapan Ditutup';
        } else {
            statusDot.classList.remove('escalated');
            statusText.textContent = 'Asisten Virtual';
        }
    }

    async function sendMessage(text) {
        if (!text.trim()) return;
        sendBtn.disabled = true;

        // Optimistic render pesan user
        appendMessages([{
            id: Date.now(),
            sender_type: 'user',
            message: text,
            created_at: new Date().toISOString(),
        }]);

        try {
            await fetch("{{ route('chat.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ message: text }),
            });
            await poll(); // langsung tarik balasan bot jika ada
        } catch (e) {
            console.error(e);
        } finally {
            sendBtn.disabled = false;
        }
    }

    async function poll() {
        try {
            const res = await fetch(`{{ route('chat.poll') }}?after_id=${lastId}`);
            const data = await res.json();
            appendMessages(data.messages);
            updateStatus(data.status);
        } catch (e) {
            console.error(e);
        }
    }

    chatForm?.addEventListener('submit', (e) => {
        e.preventDefault();
        const text = chatInput.value;
        chatInput.value = '';
        sendMessage(text);
    });

    function sendQuick(text) {
        sendMessage(text);
    }

    // Polling tiap 3 detik untuk update real-time-ish
    setInterval(poll, 3000);
</script>
@endpush