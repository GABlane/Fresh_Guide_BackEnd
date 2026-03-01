<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'FreshGuide Admin')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f6fa; }
        .sidebar { min-height: 100vh; background: #1e2a38; }
        .sidebar .nav-link { color: #adb5bd; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,.08); border-radius: 4px; }
        .sidebar .brand { color: #fff; font-weight: 700; font-size: 1.2rem; }
        .main-content { padding: 2rem; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        {{-- Sidebar --}}
        <nav class="col-md-2 sidebar d-flex flex-column py-3 px-2">
            <a class="brand mb-4 px-2 text-decoration-none" href="{{ route('admin.dashboard') }}">FreshGuide</a>
            <ul class="nav flex-column gap-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                       href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.routes.*') ? 'active' : '' }}"
                       href="{{ route('admin.routes.index') }}">Routes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}"
                       href="{{ route('admin.rooms.index') }}">Rooms</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.origins.*') ? 'active' : '' }}"
                       href="{{ route('admin.origins.index') }}">Starts (Origins)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}"
                       href="{{ route('admin.facilities.index') }}">Items (Facilities)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.buildings.*') ? 'active' : '' }}"
                       href="{{ route('admin.buildings.index') }}">Buildings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.floors.*') ? 'active' : '' }}"
                       href="{{ route('admin.floors.index') }}">Floors</a>
                </li>
            </ul>
            <div class="mt-auto px-2">
                <small class="text-secondary d-block mb-2">{{ auth()->user()->name }}</small>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-secondary w-100">Logout</button>
                </form>
            </div>
        </nav>

        {{-- Main --}}
        <main class="col-md-10 main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
