<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard &mdash; {{ config('app.name') }}</title>
    <style>
        /* ===== RESET & BASE ===== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #0f0f13;
            color: #e2e2e2;
            height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(124, 58, 237, 0.1), transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(79, 70, 229, 0.1), transparent 25%);
        }

        .dashboard-card {
            background: #18181f;
            border: 1px solid #2a2a35;
            border-radius: 24px;
            padding: 50px 40px;
            width: 100%;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            position: relative;
            overflow: hidden;
        }

        /* Glassmorphism shine effect */
        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 50%; height: 100%;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.03), transparent);
            transform: skewX(-20deg);
            animation: shine 6s infinite;
        }

        @keyframes shine {
            0% { left: -100%; }
            20% { left: 200%; }
            100% { left: 200%; }
        }

        .avatar-large {
            width: 80px; height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7c3aed, #4f46e5);
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; font-weight: 700; color: white;
            margin: 0 auto 20px;
            box-shadow: 0 10px 20px rgba(124, 58, 237, 0.3);
            border: 3px solid #23232e;
        }

        .welcome-text {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(to right, #e2e2e2, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sub-text {
            font-size: 0.95rem;
            color: #888;
            margin-bottom: 40px;
        }

        .actions {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .btn-primary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: linear-gradient(135deg, #7c3aed, #4f46e5);
            color: #fff;
            text-decoration: none;
            padding: 16px 24px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 1.05rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            box-shadow: 0 8px 16px rgba(124, 58, 237, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(124, 58, 237, 0.4);
            opacity: 0.95;
        }

        .btn-primary:active {
            transform: translateY(1px);
        }

        .btn-secondary {
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            color: #888;
            text-decoration: none;
            padding: 14px 24px;
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.95rem;
            border: 1px solid #3a3a4a;
            transition: all 0.2s;
            cursor: pointer;
        }

        .btn-secondary:hover {
            background: #23232e;
            color: #ef4444;
            border-color: #ef4444;
        }

        .icon {
            width: 24px;
            height: 24px;
            fill: currentColor;
        }
    </style>
</head>
<body>

    <div class="dashboard-card">
        <div class="avatar-large">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
        </div>
        
        <h1 class="welcome-text">Halo, {{ explode(' ', Auth::user()->name)[0] }}!</h1>
        <p class="sub-text">Selamat datang di MelChat. Hubungi teman-temanmu secara real-time sekarang juga.</p>
        
        <div class="actions">
            <a href="{{ route('chat.index') }}" class="btn-primary">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H5.2L4 17.2V4h16v12z"/>
                    <path d="M7 9h10v2H7zM7 12h7v2H7z"/>
                </svg>
                Buka Aplikasi Chat
            </a>

            <form method="POST" action="{{ route('logout') }}" style="width: 100%;">
                @csrf
                <button type="submit" class="btn-secondary" style="width: 100%;">
                    Log Out
                </button>
            </form>
        </div>
    </div>

</body>
</html>
