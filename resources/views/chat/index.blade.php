<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat &mdash; {{ config('app.name') }}</title>
    @vite(['resources/js/app.js'])
    <style>
        /* ===== RESET & BASE ===== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #0f0f13;
            color: #e2e2e2;
            height: 100dvh;
            display: flex;
            overflow: hidden;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 280px;
            min-width: 280px;
            background: #18181f;
            border-right: 1px solid #2a2a35;
            display: flex;
            flex-direction: column;
            height: 100dvh;
        }

        .sidebar-header {
            padding: 18px 16px 14px;
            border-bottom: 1px solid #2a2a35;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-header .app-name {
            font-weight: 700;
            font-size: 1.1rem;
            color: #a78bfa;
            flex: 1;
        }

        .sidebar-header .user-name {
            font-size: 0.78rem;
            color: #888;
        }

        .btn-logout {
            background: none;
            border: 1px solid #3a3a4a;
            color: #888;
            border-radius: 8px;
            padding: 5px 10px;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all .2s;
            text-decoration: none;
        }
        .btn-logout:hover { border-color: #ef4444; color: #ef4444; }

        /* ===== SECTION HEADERS ===== */
        .section-title {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #555;
            padding: 14px 16px 6px;
        }

        /* ===== CONTACT LIST ===== */
        .contact-list { overflow-y: auto; flex: 1; }
        .contact-list::-webkit-scrollbar { width: 4px; }
        .contact-list::-webkit-scrollbar-thumb { background: #2a2a35; border-radius: 4px; }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            cursor: pointer;
            border-radius: 10px;
            margin: 2px 8px;
            text-decoration: none;
            color: inherit;
            transition: background .15s;
            position: relative;
        }
        .contact-item:hover { background: #23232e; }
        .contact-item.active { background: #2d2044; }

        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            flex-shrink: 0;
        }
        .avatar-user { background: linear-gradient(135deg, #7c3aed, #4f46e5); }
        .avatar-group { background: linear-gradient(135deg, #0891b2, #0d9488); }

        .contact-info { flex: 1; min-width: 0; }
        .contact-name { font-size: 0.88rem; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .contact-sub { font-size: 0.72rem; color: #666; }

        /* Online indicator */
        .online-dot {
            width: 8px; height: 8px;
            background: #22c55e;
            border-radius: 50%;
            border: 2px solid #18181f;
            position: absolute;
            bottom: 10px; left: 40px;
        }

        /* ===== CREATE GROUP BUTTON ===== */
        .btn-create-group {
            margin: 8px 14px 12px;
            background: linear-gradient(135deg, #7c3aed, #4f46e5);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 9px 12px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            width: calc(100% - 28px);
            transition: opacity .2s;
        }
        .btn-create-group:hover { opacity: .85; }

        /* ===== MAIN CHAT AREA ===== */
        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100dvh;
            background: #0f0f13;
        }

        /* ===== CHAT HEADER ===== */
        .chat-header {
            padding: 14px 20px;
            border-bottom: 1px solid #2a2a35;
            background: #18181f;
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 62px;
        }
        .chat-header .chat-title { font-weight: 700; font-size: 1rem; }
        .chat-header .chat-sub { font-size: 0.75rem; color: #666; margin-top: 1px; }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            color: #444;
        }
        .empty-state .icon { font-size: 3rem; }
        .empty-state p { font-size: 0.9rem; }

        /* ===== MESSAGES ===== */
        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 16px 20px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .messages-container::-webkit-scrollbar { width: 4px; }
        .messages-container::-webkit-scrollbar-thumb { background: #2a2a35; border-radius: 4px; }

        .msg-row {
            display: flex;
            align-items: flex-end;
            gap: 8px;
        }
        .msg-row > div {
            display: flex;
            flex-direction: column;
            max-width: 75%;
        }
        .msg-row.mine { flex-direction: row-reverse; }
        .msg-row.mine > div { align-items: flex-end; }

        .msg-avatar {
            width: 26px; height: 26px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7c3aed, #4f46e5);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.65rem; font-weight: 700;
            flex-shrink: 0;
        }

        .msg-bubble {
            padding: 9px 13px;
            border-radius: 16px;
            font-size: 0.88rem;
            line-height: 1.45;
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: normal;
            width: fit-content;
        }
        .msg-row:not(.mine) .msg-bubble {
            background: #23232e;
            border-bottom-left-radius: 4px;
            color: #e2e2e2;
        }
        .msg-row.mine .msg-bubble {
            background: linear-gradient(135deg, #7c3aed, #4f46e5);
            border-bottom-right-radius: 4px;
            color: #fff;
        }

        .msg-meta {
            font-size: 0.65rem;
            color: #555;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .msg-row.mine .msg-meta { justify-content: flex-end; }

        .msg-sender-name {
            font-size: 0.7rem;
            font-weight: 600;
            color: #a78bfa;
            margin-bottom: 2px;
        }

        /* Date separator */
        .date-separator {
            text-align: center;
            font-size: 0.7rem;
            color: #444;
            margin: 10px 0;
            position: relative;
        }
        .date-separator::before, .date-separator::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 38%;
            height: 1px;
            background: #2a2a35;
        }
        .date-separator::before { left: 0; }
        .date-separator::after { right: 0; }

        /* ===== MESSAGE INPUT ===== */
        .input-area {
            padding: 12px 16px;
            border-top: 1px solid #2a2a35;
            background: #18181f;
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }

        .msg-input {
            flex: 1;
            background: #23232e;
            border: 1px solid #2a2a35;
            border-radius: 22px;
            padding: 10px 16px;
            color: #e2e2e2;
            font-size: 0.9rem;
            resize: none;
            outline: none;
            max-height: 120px;
            min-height: 42px;
            transition: border-color .2s;
            font-family: inherit;
        }
        .msg-input:focus { border-color: #7c3aed; }
        .msg-input::placeholder { color: #555; }

        .btn-send {
            width: 42px; height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7c3aed, #4f46e5);
            border: none;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            transition: opacity .2s, transform .1s;
        }
        .btn-send:hover { opacity: .85; }
        .btn-send:active { transform: scale(.93); }
        .btn-send svg { width: 18px; height: 18px; fill: white; }

        /* ===== MODAL CREATE GROUP ===== */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,.7);
            display: none;
            align-items: center; justify-content: center;
            z-index: 100;
        }
        .modal-overlay.open { display: flex; }

        .modal {
            background: #18181f;
            border: 1px solid #2a2a35;
            border-radius: 16px;
            padding: 24px;
            width: 360px;
            max-width: 95vw;
        }
        .modal h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: 16px; color: #a78bfa; }

        .form-group { margin-bottom: 14px; }
        .form-group label { display: block; font-size: 0.78rem; color: #888; margin-bottom: 5px; }
        .form-input {
            width: 100%;
            background: #23232e;
            border: 1px solid #2a2a35;
            border-radius: 10px;
            padding: 9px 12px;
            color: #e2e2e2;
            font-size: 0.88rem;
            outline: none;
            transition: border-color .2s;
        }
        .form-input:focus { border-color: #7c3aed; }

        .members-check { display: flex; flex-direction: column; gap: 6px; max-height: 150px; overflow-y: auto; }
        .member-check-item { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; cursor: pointer; }
        .member-check-item input { accent-color: #7c3aed; }

        .modal-actions { display: flex; gap: 8px; justify-content: flex-end; margin-top: 18px; }
        .btn-cancel {
            background: none; border: 1px solid #3a3a4a; color: #888;
            border-radius: 8px; padding: 8px 16px; font-size: 0.82rem; cursor: pointer;
            transition: all .2s;
        }
        .btn-cancel:hover { border-color: #ef4444; color: #ef4444; }
        .btn-submit {
            background: linear-gradient(135deg, #7c3aed, #4f46e5);
            color: #fff; border: none; border-radius: 8px;
            padding: 8px 18px; font-size: 0.82rem; font-weight: 600; cursor: pointer;
            transition: opacity .2s;
        }
        .btn-submit:hover { opacity: .85; }

        /* ===== TYPING INDICATOR ===== */
        .typing-indicator {
            display: none;
            padding: 4px 20px;
            font-size: 0.75rem;
            color: #666;
            font-style: italic;
        }

        /* ===== ONLINE LIST (presence) ===== */
        .online-users-bar {
            padding: 6px 14px;
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            border-bottom: 1px solid #2a2a35;
            background: #18181f;
            min-height: 36px;
            align-items: center;
        }
        .online-badge {
            font-size: 0.7rem;
            background: #1a2e1a;
            color: #4ade80;
            border-radius: 20px;
            padding: 2px 9px;
            border: 1px solid #2a3d2a;
        }
        .online-label { font-size: 0.7rem; color: #444; }
    </style>
</head>
<body>

{{-- ===== SIDEBAR ===== --}}
<aside class="sidebar">
    <div class="sidebar-header">
        <div style="flex:1">
            <div class="app-name">💬 MelChat</div>
            <div class="user-name">{{ Auth::user()->name }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Keluar</button>
        </form>
    </div>

    <div class="contact-list">
        {{-- DAFTAR USER --}}
        <div class="section-title">Kontak</div>
        @foreach($users as $u)
            <a href="{{ route('chat.private', $u->id) }}"
               class="contact-item {{ (isset($user) && $user->id === $u->id) ? 'active' : '' }}"
               id="contact-user-{{ $u->id }}">
                <div class="avatar avatar-user">{{ strtoupper(substr($u->name, 0, 2)) }}</div>
                <div class="contact-info">
                    <div class="contact-name">{{ $u->name }}</div>
                    <div class="contact-sub">{{ $u->email }}</div>
                </div>
                <span class="online-dot" id="dot-{{ $u->id }}" style="display:none"></span>
            </a>
        @endforeach

        {{-- DAFTAR GRUP --}}
        <div class="section-title" style="margin-top:8px">Grup</div>
        @foreach($groups as $g)
            <a href="{{ route('chat.group', $g->id) }}"
               class="contact-item {{ (isset($group) && $group->id === $g->id) ? 'active' : '' }}">
                <div class="avatar avatar-group">{{ strtoupper(substr($g->name, 0, 2)) }}</div>
                <div class="contact-info">
                    <div class="contact-name">{{ $g->name }}</div>
                    <div class="contact-sub">{{ $g->users->count() }} anggota</div>
                </div>
            </a>
        @endforeach

        @if($groups->isEmpty())
            <div style="font-size:.76rem; color:#444; padding: 8px 16px;">Belum ada grup.</div>
        @endif
    </div>

    {{-- TOMBOL BUAT GRUP --}}
    <button class="btn-create-group" id="btn-open-modal">+ Buat Grup Baru</button>
</aside>

{{-- ===== CHAT AREA ===== --}}
<main class="chat-area">

    @if(!isset($messages))
    {{-- EMPTY STATE: belum pilih kontak --}}
    <div class="empty-state">
        <div class="icon">💬</div>
        <p>Pilih kontak atau grup untuk mulai mengobrol</p>
    </div>

    @else
    {{-- HEADER CHAT --}}
    <div class="chat-header">
        @if(isset($user))
            <div class="avatar avatar-user">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
            <div>
                <div class="chat-title">{{ $user->name }}</div>
                <div class="chat-sub" id="chat-status">●&nbsp; online</div>
            </div>
        @elseif(isset($group))
            <div class="avatar avatar-group">{{ strtoupper(substr($group->name, 0, 2)) }}</div>
            <div>
                <div class="chat-title">{{ $group->name }}</div>
                <div class="chat-sub">Grup · {{ $group->users->count() }} anggota</div>
            </div>
        @endif
    </div>

    {{-- BAR ONLINE (presence) --}}
    <div class="online-users-bar" id="online-bar">
        <span class="online-label">🟢 Online:</span>
        <span id="online-list">—</span>
    </div>

    {{-- PESAN --}}
    <div class="messages-container" id="messages-box">
        @forelse($messages as $msg)
            @php $isMine = $msg->sender_id === Auth::id(); @endphp
            <div class="msg-row {{ $isMine ? 'mine' : '' }}" id="msg-{{ $msg->id }}">
                @if(!$isMine)
                    <div class="msg-avatar">{{ strtoupper(substr($msg->sender->name, 0, 2)) }}</div>
                @endif
                <div>
                    @if(!$isMine)
                        <div class="msg-sender-name">{{ $msg->sender->name }}</div>
                    @endif
                    <div class="msg-bubble">{{ $msg->message }}</div>
                    <div class="msg-meta">{{ $msg->created_at->format('H:i') }}</div>
                </div>
            </div>
        @empty
            <div class="empty-state" style="flex:1">
                <div class="icon">👋</div>
                <p>Belum ada pesan. Mulai obrolan!</p>
            </div>
        @endforelse
    </div>

    {{-- TYPING INDICATOR --}}
    <div class="typing-indicator" id="typing-indicator">sedang mengetik...</div>

    {{-- INPUT --}}
    <div class="input-area">
        <textarea
            class="msg-input"
            id="msg-input"
            placeholder="Tulis pesan..."
            rows="1"
            autofocus></textarea>
        <button class="btn-send" id="btn-send" title="Kirim">
            <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
        </button>
    </div>
    @endif

</main>

{{-- ===== MODAL BUAT GRUP ===== --}}
<div class="modal-overlay" id="modal-overlay">
    <div class="modal">
        <h3>Buat Grup Baru</h3>
        <form method="POST" action="{{ route('groups.store') }}">
            @csrf
            <div class="form-group">
                <label>Nama Grup</label>
                <input type="text" name="name" class="form-input" placeholder="Contoh: Tim Proyek" required>
            </div>
            <div class="form-group">
                <label>Pilih Anggota</label>
                <div class="members-check">
                    @foreach($users as $u)
                        <label class="member-check-item">
                            <input type="checkbox" name="member_ids[]" value="{{ $u->id }}">
                            {{ $u->name }}
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" id="btn-close-modal">Batal</button>
                <button type="submit" class="btn-submit">Buat Grup</button>
            </div>
        </form>
    </div>
</div>

<script type="module">
// ============================================================
// DATA DARI SERVER (dioper ke JavaScript)
// ============================================================
const AUTH_USER_ID   = {{ Auth::id() }};
const CHANNEL_NAME   = "{{ $channelName ?? '' }}";
@if(isset($user))
    const RECEIVER_ID = {{ $user->id }};
    const GROUP_ID    = null;
    const CHAT_TYPE   = 'private';
@elseif(isset($group))
    const RECEIVER_ID = null;
    const GROUP_ID    = {{ $group->id }};
    const CHAT_TYPE   = 'group';
@else
    const RECEIVER_ID = null;
    const GROUP_ID    = null;
    const CHAT_TYPE   = null;
@endif

// ============================================================
// SCROLL KE BAWAH OTOMATIS SAAT BUKA HALAMAN
// ============================================================
const messagesBox = document.getElementById('messages-box');
if (messagesBox) {
    messagesBox.scrollTop = messagesBox.scrollHeight;
}

// ============================================================
// KIRIM PESAN VIA FETCH (AJAX — tanpa reload halaman)
// ============================================================
const btnSend  = document.getElementById('btn-send');
const msgInput = document.getElementById('msg-input');

function sendMessage() {
    const text = msgInput.value.trim();
    if (!text || !CHAT_TYPE) return;

    // Tampilkan pesan langsung di layar (optimistic UI)
    appendMessage({
        id: 'temp-' + Date.now(),
        message: text,
        sender_id: AUTH_USER_ID,
        sender_name: 'Kamu',
        created_at: new Date().toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'}),
    }, true);

    msgInput.value = '';
    msgInput.style.height = 'auto';

    // Kirim ke server
    fetch("{{ route('chat.send') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            message:     text,
            receiver_id: RECEIVER_ID,
            group_id:    GROUP_ID,
        }),
    }).catch(err => console.error('Gagal kirim:', err));
}

if (btnSend) {
    btnSend.addEventListener('click', sendMessage);
}
if (msgInput) {
    // Enter = kirim, Shift+Enter = baris baru
    msgInput.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
    // Auto-resize textarea
    msgInput.addEventListener('input', () => {
        msgInput.style.height = 'auto';
        msgInput.style.height = Math.min(msgInput.scrollHeight, 120) + 'px';
    });
}

// ============================================================
// FUNGSI BUAT BUBBLE PESAN
// ============================================================
function appendMessage(data, isMine) {
    if (!messagesBox) return;

    // Hapus empty state jika ada
    const emptyState = messagesBox.querySelector('.empty-state');
    if (emptyState) emptyState.remove();

    const row = document.createElement('div');
    row.className = 'msg-row' + (isMine ? ' mine' : '');
    row.id = 'msg-' + data.id;

    const avatarHtml = !isMine
        ? `<div class="msg-avatar">${data.sender_name.substring(0,2).toUpperCase()}</div>`
        : '';
    const senderHtml = !isMine
        ? `<div class="msg-sender-name">${data.sender_name}</div>`
        : '';

    row.innerHTML = `
        ${avatarHtml}
        <div>
            ${senderHtml}
            <div class="msg-bubble">${escapeHtml(data.message)}</div>
            <div class="msg-meta">${data.created_at}</div>
        </div>
    `;

    messagesBox.appendChild(row);
    messagesBox.scrollTop = messagesBox.scrollHeight;
}

function escapeHtml(str) {
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
              .replace(/"/g,'&quot;').replace(/'/g,'&#039;');
}

// ============================================================
// WEBSOCKET: DENGARKAN PESAN MASUK LEWAT LARAVEL ECHO
// ============================================================
if (CHANNEL_NAME && window.Echo) {
    window.Echo.private(CHANNEL_NAME)
        .listen('.MessageSent', (data) => {
            // Jangan tampilkan pesan dari diri sendiri (sudah ditampilkan via optimistic UI)
            if (data.sender_id === AUTH_USER_ID) return;
            appendMessage(data, false);
        });
}

// ============================================================
// PRESENCE CHANNEL — TRACKING SIAPA YANG ONLINE
// ============================================================
const onlineList = document.getElementById('online-list');

if (window.Echo) {
    window.Echo.join('online')
        .here((users) => {
            // users = array semua user yang sedang online
            updateOnlineList(users);
            // Tandai titik hijau di sidebar
            users.forEach(u => {
                const dot = document.getElementById('dot-' + u.id);
                if (dot) dot.style.display = 'block';
            });
        })
        .joining((user) => {
            // Satu user baru masuk
            addToOnlineList(user);
            const dot = document.getElementById('dot-' + user.id);
            if (dot) dot.style.display = 'block';
        })
        .leaving((user) => {
            // Satu user keluar/offline
            removeFromOnlineList(user);
            const dot = document.getElementById('dot-' + user.id);
            if (dot) dot.style.display = 'none';
        });
}

let onlineUsers = [];

function updateOnlineList(users) {
    onlineUsers = users.filter(u => u.id !== AUTH_USER_ID);
    renderOnlineList();
}
function addToOnlineList(user) {
    if (user.id === AUTH_USER_ID) return;
    if (!onlineUsers.find(u => u.id === user.id)) onlineUsers.push(user);
    renderOnlineList();
}
function removeFromOnlineList(user) {
    onlineUsers = onlineUsers.filter(u => u.id !== user.id);
    renderOnlineList();
}
function renderOnlineList() {
    if (!onlineList) return;
    if (onlineUsers.length === 0) {
        onlineList.innerHTML = '<span style="color:#444">Tidak ada yang online</span>';
        return;
    }
    onlineList.innerHTML = onlineUsers
        .map(u => `<span class="online-badge">${u.name}</span>`)
        .join('');
}

// ============================================================
// MODAL BUAT GRUP
// ============================================================
const modalOverlay = document.getElementById('modal-overlay');
document.getElementById('btn-open-modal')?.addEventListener('click', () => {
    modalOverlay.classList.add('open');
});
document.getElementById('btn-close-modal')?.addEventListener('click', () => {
    modalOverlay.classList.remove('open');
});
modalOverlay?.addEventListener('click', (e) => {
    if (e.target === modalOverlay) modalOverlay.classList.remove('open');
});
</script>
</body>
</html>
