<!doctype html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In · FreshGuide</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700;0,9..144,900;1,9..144,500&family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        [data-theme="light"] {
            --bg:          #fdf8f0;
            --surface:     #ffffff;
            --surface2:    #fdf4e7;
            --border:      #ede4d4;
            --shadow:      rgba(120,80,20,.1);
            --primary:     #7c3aed;
            --primary-soft:#ede9fe;
            --primary-dim: #6d28d9;
            --secondary:   #f59e0b;
            --rose:        #e11d48;
            --rose-soft:   #ffe4e6;
            --txt:         #1c1030;
            --txt-muted:   #7c6f8e;
            --txt-dim:     #c4b8d4;
        }
        [data-theme="dark"] {
            --bg:          #120f1e;
            --surface:     #1c1830;
            --surface2:    #251f3a;
            --border:      #352c50;
            --shadow:      rgba(0,0,0,.35);
            --primary:     #a78bfa;
            --primary-soft:#2d2352;
            --primary-dim: #8b6ff0;
            --secondary:   #fbbf24;
            --rose:        #fb7185;
            --rose-soft:   #3b0a18;
            --txt:         #f0eaff;
            --txt-muted:   #9d8fc0;
            --txt-dim:     #483d6a;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Nunito', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: grid;
            place-items: center;
            color: var(--txt);
            transition: background .3s, color .3s;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Decorative blobs */
        body::before {
            content: '';
            position: fixed;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(124,58,237,.12) 0%, transparent 70%);
            top: -120px; right: -120px;
            border-radius: 50%;
            pointer-events: none;
        }
        body::after {
            content: '';
            position: fixed;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(245,158,11,.1) 0%, transparent 70%);
            bottom: -100px; left: -100px;
            border-radius: 50%;
            pointer-events: none;
        }

        .login-wrap {
            width: 100%; max-width: 420px;
            position: relative; z-index: 1;
            animation: popIn .45s cubic-bezier(.22,1,.36,1) both;
        }
        @keyframes popIn {
            from { opacity:0; transform:scale(.96) translateY(14px); }
            to   { opacity:1; transform:scale(1)   translateY(0); }
        }

        /* Brand */
        .login-brand { text-align: center; margin-bottom: 28px; }
        .brand-icon {
            width: 64px; height: 64px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 18px;
            display: grid; place-items: center;
            font-size: 30px;
            margin: 0 auto 14px;
            box-shadow: 0 8px 24px rgba(124,58,237,.35);
        }
        .login-brand h1 {
            font-family: 'Fraunces', serif;
            font-size: 28px; font-weight: 900;
            color: var(--txt);
        }
        .login-brand p {
            font-size: 14px; color: var(--txt-muted);
            font-style: italic; margin-top: 4px;
        }

        /* Card */
        .login-card {
            background: var(--surface);
            border-radius: 20px;
            box-shadow: 0 8px 32px var(--shadow);
            padding: 32px;
            transition: background .3s;
        }

        .error-box {
            background: var(--rose-soft);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13.5px; font-weight: 600;
            color: var(--rose);
            margin-bottom: 22px;
            display: flex; align-items: center; gap: 8px;
        }

        .field { margin-bottom: 18px; }
        label {
            display: block;
            font-size: 13px; font-weight: 800;
            color: var(--txt-muted);
            margin-bottom: 7px;
        }
        input[type=email], input[type=password] {
            width: 100%; padding: 11px 15px;
            background: var(--surface2);
            border: 2px solid transparent;
            border-radius: 10px;
            color: var(--txt);
            font-family: 'Nunito', sans-serif;
            font-size: 15px; font-weight: 500;
            outline: none;
            transition: border-color .18s, box-shadow .18s;
            appearance: none;
        }
        input:focus {
            border-color: var(--primary);
            background: var(--surface);
            box-shadow: 0 0 0 4px var(--primary-soft);
        }
        input.err { border-color: var(--rose) !important; }
        input::placeholder { color: var(--txt-dim); font-weight: 400; }
        .field-err { font-size: 12px; color: var(--rose); font-weight: 700; margin-top: 5px; }

        .remember {
            display: flex; align-items: center; gap: 9px;
            font-size: 13.5px; font-weight: 600;
            color: var(--txt-muted);
            margin-bottom: 24px; cursor: pointer;
        }
        .remember input { width: 15px; height: 15px; accent-color: var(--primary); flex-shrink: 0; }

        .btn-submit {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dim));
            border: none; border-radius: 12px;
            color: #fff;
            font-family: 'Fraunces', serif;
            font-size: 17px; font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 18px rgba(124,58,237,.38);
            transition: all .18s;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(124,58,237,.48); }
        .btn-submit:active { transform: scale(.98); }

        /* Theme + footer */
        .login-footer {
            text-align: center; margin-top: 22px;
            display: flex; flex-direction: column;
            align-items: center; gap: 12px;
        }
        .theme-btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 7px 16px;
            background: var(--surface2);
            border: none; border-radius: 999px;
            color: var(--txt-muted);
            font-family: 'Nunito', sans-serif;
            font-size: 13px; font-weight: 700;
            cursor: pointer; transition: all .18s;
        }
        .theme-btn:hover { background: var(--primary-soft); color: var(--primary); }
        .footer-text { font-size: 12px; color: var(--txt-dim); font-style: italic; }
    </style>
</head>
<body>
<div class="login-wrap">
    <div class="login-brand">
        <div class="brand-icon">🗺️</div>
        <h1>FreshGuide</h1>
        <p>Campus Navigation Admin</p>
    </div>

    <div class="login-card">
        @if($errors->any())
            <div class="error-box">⚠️ {{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="field">
                <label>Email address</label>
                <input type="email" name="email"
                       class="{{ $errors->has('email') ? 'err' : '' }}"
                       value="{{ old('email') }}"
                       placeholder="you@freshguide.com"
                       required autofocus>
                @error('email')<div class="field-err">{{ $message }}</div>@enderror
            </div>
            <div class="field" style="margin-bottom:22px;">
                <label>Password</label>
                <input type="password" name="password"
                       class="{{ $errors->has('password') ? 'err' : '' }}"
                       placeholder="••••••••" required>
                @error('password')<div class="field-err">{{ $message }}</div>@enderror
            </div>
            <label class="remember">
                <input type="checkbox" name="remember"> Keep me signed in
            </label>
            <button type="submit" class="btn-submit">Let's go →</button>
        </form>
    </div>

    <div class="login-footer">
        <button class="theme-btn" id="themeToggle">
            <span id="themeIcon">🌙</span>
            <span id="themeLabel">Switch to dark</span>
        </button>
        <span class="footer-text">UCC Campus Navigation System</span>
    </div>
</div>

<script>
(function () {
    const html  = document.documentElement;
    const btn   = document.getElementById('themeToggle');
    const icon  = document.getElementById('themeIcon');
    const label = document.getElementById('themeLabel');

    function apply(theme) {
        html.setAttribute('data-theme', theme);
        const dark = theme === 'dark';
        icon.textContent  = dark ? '☀️' : '🌙';
        label.textContent = dark ? 'Switch to light' : 'Switch to dark';
        localStorage.setItem('fg-theme', theme);
    }

    apply(localStorage.getItem('fg-theme') || 'light');
    btn.addEventListener('click', () =>
        apply(html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark'));
})();
</script>
</body>
</html>
