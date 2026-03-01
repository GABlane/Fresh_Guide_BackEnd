<!doctype html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'FreshGuide') · Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,700;1,9..144,400;1,9..144,600&family=Nunito:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    <style>
        /* ── Tokens ─────────────────────────────────────────────── */
        [data-theme="light"] {
            --bg:           #fdf8f0;
            --surface:      #ffffff;
            --surface2:     #fdf4e7;
            --surface3:     #f7ead6;
            --border:       #ede4d4;
            --shadow:       rgba(120, 80, 20, .08);
            --shadow-md:    rgba(120, 80, 20, .14);
            --primary:      #7c3aed;
            --primary-soft: #ede9fe;
            --primary-dim:  #6d28d9;
            --secondary:    #f59e0b;
            --secondary-soft:#fef3c7;
            --green:        #059669;
            --green-soft:   #d1fae5;
            --rose:         #e11d48;
            --rose-soft:    #ffe4e6;
            --txt:          #1c1030;
            --txt-muted:    #7c6f8e;
            --txt-dim:      #c4b8d4;
            --sidebar-w:    240px;
            --radius:       14px;
            --radius-sm:    8px;
            --radius-pill:  999px;
        }
        [data-theme="dark"] {
            --bg:           #120f1e;
            --surface:      #1c1830;
            --surface2:     #251f3a;
            --surface3:     #2e2748;
            --border:       #352c50;
            --shadow:       rgba(0,0,0,.3);
            --shadow-md:    rgba(0,0,0,.45);
            --primary:      #a78bfa;
            --primary-soft: #2d2352;
            --primary-dim:  #8b6ff0;
            --secondary:    #fbbf24;
            --secondary-soft:#2d2200;
            --green:        #34d399;
            --green-soft:   #052e16;
            --rose:         #fb7185;
            --rose-soft:    #3b0a18;
            --txt:          #f0eaff;
            --txt-muted:    #9d8fc0;
            --txt-dim:      #483d6a;
            --sidebar-w:    240px;
            --radius:       14px;
            --radius-sm:    8px;
            --radius-pill:  999px;
        }

        /* ── Reset ──────────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { transition: background .3s, color .3s; }
        body {
            font-family: 'Nunito', sans-serif;
            font-size: 14.5px;
            line-height: 1.65;
            background: var(--bg);
            color: var(--txt);
            min-height: 100vh;
            display: flex;
        }

        /* ── Sidebar ────────────────────────────────────────────── */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--surface);
            box-shadow: 2px 0 16px var(--shadow);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 22px 20px 18px;
            border-bottom: 1.5px dashed var(--border);
            text-decoration: none;
        }
        .brand-blob {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            display: grid; place-items: center;
            font-size: 20px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(124,58,237,.35);
        }
        .brand-name {
            font-family: 'Fraunces', serif;
            font-weight: 700;
            font-size: 18px;
            color: var(--txt);
            line-height: 1;
        }
        .brand-badge {
            display: inline-block;
            background: var(--primary-soft);
            color: var(--primary);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .04em;
            padding: 2px 8px;
            border-radius: var(--radius-pill);
            margin-top: 4px;
        }

        .nav-section { padding: 16px 12px 4px; }
        .nav-label {
            font-size: 10.5px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--txt-dim);
            padding: 0 10px;
            margin-bottom: 6px;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: var(--radius-sm);
            color: var(--txt-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all .18s;
            margin-bottom: 2px;
        }
        .nav-link:hover {
            background: var(--surface2);
            color: var(--txt);
            transform: translateX(2px);
        }
        .nav-link.active {
            background: var(--primary-soft);
            color: var(--primary);
        }
        .nav-icon { font-size: 16px; line-height: 1; flex-shrink: 0; }

        .sidebar-footer {
            margin-top: auto;
            padding: 16px 12px;
            border-top: 1.5px dashed var(--border);
        }
        .user-row {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 12px;
            padding: 10px 12px;
            background: var(--surface2);
            border-radius: var(--radius-sm);
        }
        .user-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--secondary), var(--rose));
            display: grid; place-items: center;
            font-weight: 800; font-size: 14px;
            color: #fff;
            flex-shrink: 0;
        }
        .user-name { font-size: 13px; font-weight: 700; color: var(--txt); }
        .user-role { font-size: 11px; color: var(--txt-muted); font-weight: 500; }
        .btn-logout {
            width: 100%;
            padding: 8px 14px;
            background: var(--rose-soft);
            border: none;
            border-radius: var(--radius-pill);
            color: var(--rose);
            font-family: 'Nunito', sans-serif;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all .18s;
        }
        .btn-logout:hover { filter: brightness(.92); transform: scale(.98); }

        /* ── Main ───────────────────────────────────────────────── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            height: 58px;
            background: var(--surface);
            box-shadow: 0 2px 12px var(--shadow);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-title {
            font-family: 'Fraunces', serif;
            font-size: 18px;
            font-weight: 600;
            color: var(--txt);
        }

        /* Theme toggle */
        .theme-toggle-btn {
            display: flex; align-items: center; gap: 8px;
            padding: 7px 14px;
            background: var(--surface2);
            border: none;
            border-radius: var(--radius-pill);
            color: var(--txt-muted);
            font-family: 'Nunito', sans-serif;
            font-size: 13px; font-weight: 700;
            cursor: pointer;
            transition: all .18s;
        }
        .theme-toggle-btn:hover { background: var(--surface3); color: var(--txt); }

        /* ── Content ────────────────────────────────────────────── */
        .content {
            padding: 28px 32px;
            animation: fadeUp .32s ease both;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Alerts ─────────────────────────────────────────────── */
        .alert {
            padding: 12px 18px;
            border-radius: var(--radius);
            font-size: 14px; font-weight: 600;
            margin-bottom: 22px;
            display: flex; align-items: center; gap: 10px;
        }
        .alert-success { background: var(--green-soft);  color: var(--green); }
        .alert-error   { background: var(--rose-soft);   color: var(--rose); }

        /* ── Cards ──────────────────────────────────────────────── */
        .card {
            background: var(--surface);
            border-radius: var(--radius);
            box-shadow: 0 2px 16px var(--shadow);
        }

        /* ── Tables ─────────────────────────────────────────────── */
        .table-wrap { overflow-x: auto; border-radius: var(--radius); }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        thead th {
            padding: 12px 18px;
            text-align: left;
            font-size: 11px; font-weight: 800;
            letter-spacing: .07em; text-transform: uppercase;
            color: var(--txt-muted);
            background: var(--surface2);
        }
        thead th:first-child { border-radius: var(--radius) 0 0 0; }
        thead th:last-child  { border-radius: 0 var(--radius) 0 0; }
        tbody tr {
            border-top: 1.5px solid var(--border);
            transition: background .12s;
        }
        tbody tr:hover { background: var(--surface2); }
        tbody td { padding: 12px 18px; vertical-align: middle; }
        .td-muted { color: var(--txt-muted); }
        .td-actions { text-align: right; white-space: nowrap; }
        .empty-row td {
            text-align: center;
            padding: 56px;
            color: var(--txt-dim);
            font-family: 'Fraunces', serif;
            font-style: italic;
            font-size: 17px;
        }

        /* ── Buttons ────────────────────────────────────────────── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 18px;
            border-radius: var(--radius-pill);
            font-size: 13.5px; font-weight: 700;
            cursor: pointer; text-decoration: none;
            border: none;
            transition: all .18s;
            font-family: 'Nunito', sans-serif;
            white-space: nowrap;
        }
        .btn:active { transform: scale(.97); }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dim));
            color: #fff;
            box-shadow: 0 4px 14px rgba(124,58,237,.3);
        }
        .btn-primary:hover { box-shadow: 0 6px 20px rgba(124,58,237,.45); transform: translateY(-1px); }
        .btn-ghost {
            background: var(--surface2);
            color: var(--txt-muted);
        }
        .btn-ghost:hover { background: var(--surface3); color: var(--txt); }
        .btn-danger {
            background: transparent;
            color: var(--txt-dim);
        }
        .btn-danger:hover { background: var(--rose-soft); color: var(--rose); }
        .btn-lg { padding: 12px 28px; font-size: 15.5px; }
        .btn-sm { padding: 5px 12px; font-size: 12px; }

        /* ── Forms ──────────────────────────────────────────────── */
        .form-wrap { max-width: 620px; }
        .form-wide { max-width: 840px; }
        .field { margin-bottom: 20px; }
        label {
            display: block;
            font-size: 12.5px; font-weight: 800;
            letter-spacing: .03em;
            color: var(--txt-muted);
            margin-bottom: 6px;
            text-transform: none;
        }
        label .req { color: var(--rose); margin-left: 2px; }
        input[type=text], input[type=email], input[type=password],
        input[type=number], input[type=url], textarea, select {
            width: 100%;
            padding: 10px 14px;
            background: var(--surface2);
            border: 2px solid transparent;
            border-radius: var(--radius-sm);
            color: var(--txt);
            font-family: 'Nunito', sans-serif;
            font-size: 14.5px; font-weight: 500;
            outline: none;
            transition: border-color .18s, box-shadow .18s, background .3s;
            appearance: none; -webkit-appearance: none;
        }
        input:focus, textarea:focus, select:focus {
            border-color: var(--primary);
            background: var(--surface);
            box-shadow: 0 0 0 4px var(--primary-soft);
        }
        input::placeholder, textarea::placeholder { color: var(--txt-dim); font-weight: 400; }
        textarea { resize: vertical; }
        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%239d8fc0' stroke-width='2.5' stroke-linecap='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 12px center;
            padding-right: 36px; cursor: pointer;
        }
        input.err, select.err, textarea.err { border-color: var(--rose) !important; }
        .field-hint { font-size: 12px; color: var(--txt-dim); margin-top: 5px; }
        .field-err  { font-size: 12px; color: var(--rose);    margin-top: 5px; font-weight: 700; }

        /* Checkboxes */
        .check-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(155px,1fr)); gap: 8px; }
        .check-item {
            display: flex; align-items: center; gap: 9px;
            padding: 9px 13px;
            background: var(--surface2);
            border: 2px solid transparent;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: all .18s;
        }
        .check-item:has(input:checked) {
            border-color: var(--primary);
            background: var(--primary-soft);
        }
        .check-item input[type=checkbox] { width: 15px; height: 15px; accent-color: var(--primary); flex-shrink: 0; }
        .check-item span {
            font-size: 13.5px; font-weight: 600;
            color: var(--txt-muted);
            font-family: 'Nunito', sans-serif;
            letter-spacing: 0; text-transform: none;
        }
        .check-item:has(input:checked) span { color: var(--primary); }

        /* ── Code ───────────────────────────────────────────────── */
        code {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            background: var(--secondary-soft);
            border-radius: 6px;
            padding: 2px 8px;
            color: var(--secondary);
            font-weight: 700;
        }

        /* ── Page header ────────────────────────────────────────── */
        .page-header {
            display: flex; align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 16px;
        }
        .page-header h1 {
            font-family: 'Fraunces', serif;
            font-size: 28px; font-weight: 700;
            color: var(--txt); line-height: 1.1;
        }
        .page-header .sub {
            font-size: 13.5px; font-style: italic;
            color: var(--txt-muted); margin-top: 3px;
        }

        /* ── Breadcrumb ─────────────────────────────────────────── */
        .breadcrumb {
            font-size: 13px; font-weight: 600;
            color: var(--txt-dim);
            margin-bottom: 18px;
            display: flex; align-items: center; gap: 6px;
        }
        .breadcrumb a { color: var(--txt-muted); text-decoration: none; }
        .breadcrumb a:hover { color: var(--primary); }

        /* ── Form card ──────────────────────────────────────────── */
        .form-card {
            background: var(--surface);
            border-radius: var(--radius);
            box-shadow: 0 2px 20px var(--shadow);
            padding: 32px;
        }
        .form-card h2 {
            font-family: 'Fraunces', serif;
            font-size: 22px; font-weight: 700;
            margin-bottom: 24px; color: var(--txt);
        }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media (max-width:600px) { .form-row { grid-template-columns: 1fr; } }
        .form-actions {
            display: flex; gap: 10px;
            margin-top: 28px; padding-top: 22px;
            border-top: 1.5px dashed var(--border);
        }

        /* ── Steps ──────────────────────────────────────────────── */
        .steps-header {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }
        .steps-title {
            font-family: 'Fraunces', serif;
            font-size: 17px; font-weight: 600;
            color: var(--txt);
        }
        .step-card {
            background: var(--surface2);
            border-radius: var(--radius);
            padding: 18px 20px;
            margin-bottom: 12px;
            border-left: 4px solid var(--primary);
            box-shadow: 0 2px 10px var(--shadow);
        }
        .step-card-header {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }
        .step-badge {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 13px; font-weight: 800;
            color: var(--primary);
            background: var(--primary-soft);
            padding: 4px 12px;
            border-radius: var(--radius-pill);
        }
        .add-step-btn {
            width: 100%; padding: 12px;
            background: var(--surface2);
            border: 2px dashed var(--border);
            border-radius: var(--radius);
            color: var(--txt-muted);
            font-family: 'Nunito', sans-serif;
            font-size: 13.5px; font-weight: 700;
            cursor: pointer; transition: all .18s;
        }
        .add-step-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-soft);
        }

        /* ── Section divider ─────────────────────────────────────── */
        .section-divider {
            display: flex; align-items: center; gap: 14px;
            margin: 28px 0 20px;
        }
        .section-divider::before, .section-divider::after {
            content: ''; flex: 1;
            border-top: 1.5px dashed var(--border);
        }
        .section-divider span {
            font-size: 13px; font-weight: 800;
            color: var(--txt-muted);
            white-space: nowrap;
        }
    </style>
</head>
<body>

<aside class="sidebar">
    <a class="sidebar-brand" href="{{ route('admin.dashboard') }}">
        <div class="brand-blob">🗺️</div>
        <div>
            <div class="brand-name">FreshGuide</div>
            <span class="brand-badge">Admin</span>
        </div>
    </a>

    <div class="nav-section">
        <div class="nav-label">Overview</div>
        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">🏠</span> Dashboard
        </a>
    </div>

    <div class="nav-section">
        <div class="nav-label">Campus Data</div>
        <a href="{{ route('admin.routes.index') }}"
           class="nav-link {{ request()->routeIs('admin.routes.*') ? 'active' : '' }}">
            <span class="nav-icon">↗️</span> Routes
        </a>
        <a href="{{ route('admin.rooms.index') }}"
           class="nav-link {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
            <span class="nav-icon">🚪</span> Rooms
        </a>
        <a href="{{ route('admin.origins.index') }}"
           class="nav-link {{ request()->routeIs('admin.origins.*') ? 'active' : '' }}">
            <span class="nav-icon">📍</span> Starts
        </a>
        <a href="{{ route('admin.facilities.index') }}"
           class="nav-link {{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}">
            <span class="nav-icon">✨</span> Items
        </a>
    </div>

    <div class="nav-section">
        <div class="nav-label">Structure</div>
        <a href="{{ route('admin.buildings.index') }}"
           class="nav-link {{ request()->routeIs('admin.buildings.*') ? 'active' : '' }}">
            <span class="nav-icon">🏛️</span> Buildings
        </a>
        <a href="{{ route('admin.floors.index') }}"
           class="nav-link {{ request()->routeIs('admin.floors.*') ? 'active' : '' }}">
            <span class="nav-icon">📐</span> Floors
        </a>
    </div>

    <div class="sidebar-footer">
        <div class="user-row">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name ?? '' }}</div>
                <div class="user-role">{{ ucfirst(auth()->user()->role ?? 'admin') }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="btn-logout">👋 Sign out</button>
        </form>
    </div>
</aside>

<div class="main">
    <div class="topbar">
        <div class="topbar-title">@yield('title', 'Dashboard')</div>
        <button class="theme-toggle-btn" id="themeToggle">
            <span id="themeIcon">☀️</span>
            <span id="themeLabel">Light mode</span>
        </button>
    </div>

    <div class="content">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">⚠️ {{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

<script>
(function () {
    const html   = document.documentElement;
    const btn    = document.getElementById('themeToggle');
    const icon   = document.getElementById('themeIcon');
    const label  = document.getElementById('themeLabel');

    function apply(theme) {
        html.setAttribute('data-theme', theme);
        const dark = theme === 'dark';
        icon.textContent  = dark ? '🌙' : '☀️';
        label.textContent = dark ? 'Dark mode' : 'Light mode';
        localStorage.setItem('fg-theme', theme);
    }

    apply(localStorage.getItem('fg-theme') || 'light');
    btn.addEventListener('click', () =>
        apply(html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark'));
})();
</script>

@stack('scripts')
</body>
</html>
