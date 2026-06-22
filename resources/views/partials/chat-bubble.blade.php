@php
    $conversation = \App\Models\ChatConversation::where('user_id', auth()->id())->first();
    $unreadFromAdmin = $conversation
        ? $conversation->messages()->where('sender_type', 'admin')->where('is_read', false)->count()
        : 0;
@endphp

<div id="chat-widget">
    {{-- Tombol bubble --}}
    <button id="chat-toggle" onclick="toggleChat()" aria-label="Buka Live Chat">
        <i class="fas fa-comments" id="chat-icon-open"></i>
        <i class="fas fa-times" id="chat-icon-close" style="display:none;"></i>
        @if($unreadFromAdmin > 0)
            <span class="chat-notif-dot" id="chat-notif-dot">{{ $unreadFromAdmin }}</span>
        @else
            <span class="chat-notif-dot" id="chat-notif-dot" style="display:none;">0</span>
        @endif
    </button>

    {{-- Panel chat --}}
    <div id="chat-panel">
        <div id="chat-header">
            <div style="display:flex;align-items:center;gap:0.6rem;">
                <div style="width:34px;height:34px;border-radius:50%;background:var(--gold);display:flex;align-items:center;justify-content:center;font-size:1rem;">💬</div>
                <div>
                    <div style="font-weight:700;font-size:0.875rem;color:white;">Live Chat</div>
                    <div style="font-size:0.7rem;color:rgba(255,255,255,0.6);" id="chat-status-label">
                        {{ $conversation && $conversation->status === 'open' ? 'Admin siap membantu' : 'Asisten Virtual' }}
                    </div>
                </div>
            </div>
            <button onclick="toggleChat()" style="background:none;border:none;color:white;cursor:pointer;font-size:1rem;padding:0.25rem;"><i class="fas fa-times"></i></button>
        </div>

        <div id="chat-messages">
            {{-- Pesan awal jika belum ada --}}
            @if(!$conversation || $conversation->messages()->count() === 0)
                <div class="chat-bubble-row">
                    <div class="chat-avatar bot">🤖</div>
                    <div>
                        <div class="chat-bubble bot-bubble">Halo! Ada yang bisa saya bantu? 😊</div>
                        <div class="chat-bubble-meta">Bot</div>
                    </div>
                </div>
            @else
                @foreach($conversation->messages as $msg)
                    @php $isMine = $msg->sender_type === 'user'; @endphp
                    <div class="chat-bubble-row {{ $isMine ? 'mine' : '' }}">
                        <div class="chat-avatar {{ $msg->sender_type }}">
                            {{ $msg->sender_type === 'bot' ? '🤖' : ($msg->sender_type === 'admin' ? 'A' : 'U') }}
                        </div>
                        <div>
                            <div class="chat-bubble {{ $isMine ? 'user-bubble' : 'bot-bubble' }}">{{ $msg->message }}</div>
                            <div class="chat-bubble-meta">{{ $msg->created_at->format('H:i') }}</div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div id="chat-input-area">
            <input type="text" id="chat-input" placeholder="Ketik pesan..." autocomplete="off" onkeydown="if(event.key==='Enter') sendMessage()">
            <button onclick="sendMessage()" id="chat-send-btn">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<style>
#chat-widget{position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;font-family:'Plus Jakarta Sans',sans-serif;}

#chat-toggle{width:54px;height:54px;border-radius:50%;background:var(--plum, #3D2645);border:none;cursor:pointer;color:white;font-size:1.3rem;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 20px rgba(61,38,69,0.4);transition:all 0.3s ease;position:relative;margin-left:auto;}
#chat-toggle:hover{transform:scale(1.08);background:var(--indigo, #5B4B8A);}

.chat-notif-dot{position:absolute;top:-4px;right:-4px;background:#e74c3c;color:white;font-size:0.6rem;font-weight:700;width:18px;height:18px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid white;}

#chat-panel{position:absolute;bottom:66px;right:0;width:320px;background:white;border-radius:16px;box-shadow:0 8px 40px rgba(61,38,69,0.22);display:none;flex-direction:column;overflow:hidden;border:1px solid rgba(61,38,69,0.1);animation:chatSlideUp 0.25s ease;}
#chat-panel.open{display:flex;}
@keyframes chatSlideUp{from{opacity:0;transform:translateY(12px);}to{opacity:1;transform:translateY(0);}}

#chat-header{background:var(--plum, #3D2645);padding:0.85rem 1rem;display:flex;align-items:center;justify-content:space-between;}

#chat-messages{flex:1;max-height:320px;overflow-y:auto;padding:1rem;display:flex;flex-direction:column;gap:0.75rem;background:#f9f7fb;}
#chat-messages::-webkit-scrollbar{width:3px;}
#chat-messages::-webkit-scrollbar-thumb{background:rgba(61,38,69,0.2);border-radius:3px;}

.chat-bubble-row{display:flex;align-items:flex-end;gap:0.5rem;}
.chat-bubble-row.mine{flex-direction:row-reverse;}

.chat-avatar{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;flex-shrink:0;}
.chat-avatar.bot{background:#EDE6F0;color:#3D2645;}
.chat-avatar.admin{background:#5B4B8A;color:white;}
.chat-avatar.user{background:var(--magenta, #8E3B5C);color:white;}

.chat-bubble{padding:0.55rem 0.85rem;border-radius:12px;font-size:0.82rem;line-height:1.5;max-width:210px;word-break:break-word;}
.bot-bubble{background:white;color:#3D2645;border:1px solid #EDE6F0;border-bottom-left-radius:4px;}
.user-bubble{background:var(--plum, #3D2645);color:white;border-bottom-right-radius:4px;}

.chat-bubble-meta{font-size:0.65rem;color:#9d84a8;margin-top:0.2rem;}
.chat-bubble-row.mine .chat-bubble-meta{text-align:right;}

#chat-input-area{display:flex;align-items:center;gap:0.5rem;padding:0.75rem 1rem;border-top:1px solid #EDE6F0;background:white;}
#chat-input{flex:1;border:1.5px solid #EDE6F0;border-radius:50px;padding:0.5rem 1rem;font-size:0.82rem;font-family:'Plus Jakarta Sans',sans-serif;outline:none;transition:border-color 0.2s;color:#3D2645;}
#chat-input:focus{border-color:var(--plum, #3D2645);}
#chat-send-btn{width:34px;height:34px;border-radius:50%;background:var(--plum, #3D2645);border:none;color:white;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:0.85rem;transition:background 0.2s;flex-shrink:0;}
#chat-send-btn:hover{background:var(--indigo, #5B4B8A);}

.chat-typing{display:flex;gap:4px;padding:0.5rem 0.75rem;background:white;border-radius:12px;border:1px solid #EDE6F0;width:fit-content;}
.chat-typing span{width:6px;height:6px;border-radius:50%;background:#9d84a8;animation:typingDot 1.2s infinite;}
.chat-typing span:nth-child(2){animation-delay:0.2s;}
.chat-typing span:nth-child(3){animation-delay:0.4s;}
@keyframes typingDot{0%,60%,100%{transform:translateY(0);}30%{transform:translateY(-4px);}}

@media(max-width:380px){
    #chat-panel{width:calc(100vw - 2rem);right:-0.5rem;}
}
</style>

<script>
let chatOpen = false;
let lastMsgId = {{ $conversation ? $conversation->messages()->max('id') ?? 0 : 0 }};
let pollInterval = null;

function toggleChat() {
    chatOpen = !chatOpen;
    const panel = document.getElementById('chat-panel');
    const iconOpen = document.getElementById('chat-icon-open');
    const iconClose = document.getElementById('chat-icon-close');

    if (chatOpen) {
        panel.classList.add('open');
        iconOpen.style.display = 'none';
        iconClose.style.display = 'block';
        document.getElementById('chat-notif-dot').style.display = 'none';
        scrollToBottom();
        startPolling();
    } else {
        panel.classList.remove('open');
        iconOpen.style.display = 'block';
        iconClose.style.display = 'none';
        stopPolling();
    }
}

function scrollToBottom() {
    const box = document.getElementById('chat-messages');
    box.scrollTop = box.scrollHeight;
}

function sendMessage() {
    const input = document.getElementById('chat-input');
    const msg = input.value.trim();
    if (!msg) return;

    input.value = '';
    appendMessage('user', msg, 'U');
    showTyping();

    fetch('{{ route('chat.store') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ message: msg })
    })
    .then(r => r.json())
    .then(() => {
        removeTyping();
        pollMessages();
    })
    .catch(() => removeTyping());
}

function appendMessage(type, text, avatar) {
    const box = document.getElementById('chat-messages');
    const isMine = type === 'user';
    const div = document.createElement('div');
    div.className = 'chat-bubble-row' + (isMine ? ' mine' : '');
    div.innerHTML = `
        <div class="chat-avatar ${type}">${avatar}</div>
        <div>
            <div class="chat-bubble ${isMine ? 'user-bubble' : 'bot-bubble'}">${escapeHtml(text)}</div>
            <div class="chat-bubble-meta">${new Date().toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'})}</div>
        </div>`;
    box.appendChild(div);
    scrollToBottom();
}

function showTyping() {
    const box = document.getElementById('chat-messages');
    const div = document.createElement('div');
    div.className = 'chat-bubble-row';
    div.id = 'typing-indicator';
    div.innerHTML = `<div class="chat-avatar bot">🤖</div><div class="chat-typing"><span></span><span></span><span></span></div>`;
    box.appendChild(div);
    scrollToBottom();
}

function removeTyping() {
    const t = document.getElementById('typing-indicator');
    if (t) t.remove();
}

function pollMessages() {
    fetch(`{{ route('chat.poll') }}?after_id=${lastMsgId}`)
        .then(r => r.json())
        .then(data => {
            if (data.messages && data.messages.length > 0) {
                data.messages.forEach(msg => {
                    if (msg.sender_type !== 'user') {
                        const avatar = msg.sender_type === 'bot' ? '🤖' : 'A';
                        appendMessage(msg.sender_type, msg.message, avatar);
                    }
                    lastMsgId = Math.max(lastMsgId, msg.id);
                });
            }
            if (data.status) {
                const lbl = document.getElementById('chat-status-label');
                if (lbl) lbl.textContent = data.status === 'open' ? 'Admin siap membantu' : 'Asisten Virtual';
            }
        });
}

function startPolling() {
    if (pollInterval) return;
    pollInterval = setInterval(pollMessages, 5000);
}

function stopPolling() {
    if (pollInterval) { clearInterval(pollInterval); pollInterval = null; }
}

function escapeHtml(text) {
    return text.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>