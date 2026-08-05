@php
    $settings = auth()->check() ? auth()->user()->settings()->firstOrCreate([]) : null;
    $theme = $settings?->theme ?? 'light';
@endphp
<!doctype html>
<html lang="en" data-theme="{{ $theme }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0f172a">
    <meta name="app-base-url" content="{{ url('/') }}">
    <title>@yield('title', 'Bachat')</title>
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.svg') }}">
    <link rel="stylesheet" href="{{ asset('bachat.css') }}">
    <script defer src="{{ asset('bachat.js') }}"></script>
</head>
<body class="app-shell" data-theme="{{ $theme }}">
    <div class="orb orb-a"></div>
    <div class="orb orb-b"></div>
    <div class="orb orb-c"></div>

    <header class="topbar">
        <div>
            <span class="eyebrow">Bachat</span>
            <h1>@yield('header', 'Dashboard')</h1>
        </div>
        <div class="status-stack">
            <span class="network-pill" data-network-status>Online</span>
            @if(auth()->check())
                <span class="user-chip">{{ auth()->user()->full_name }}</span>
            @endif
        </div>
    </header>

    <main class="page-shell">
        @if(session('status'))
            <div class="flash flash-success">{{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div class="flash flash-error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>

    @auth
        <nav class="bottom-nav" aria-label="Primary">
            <a class="bottom-nav__link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Home</a>
            <a class="bottom-nav__link {{ request()->routeIs('transactions.*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">Transactions</a>
            <a class="bottom-nav__link {{ request()->routeIs('summary') ? 'active' : '' }}" href="{{ route('summary') }}">Summary</a>
            <a class="bottom-nav__link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}">Settings</a>
        </nav>
    @endauth
</body>
</html>
