<!doctype html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0f172a">
    <meta name="app-base-url" content="{{ url('/') }}">
    <title>@yield('title', 'Bachat')</title>
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="{{ asset('bachat.css') }}">
    <script defer src="{{ asset('bachat.js') }}"></script>
</head>
<body class="auth-shell" data-theme="light">
    <div class="orb orb-a"></div>
    <div class="orb orb-b"></div>
    <main class="auth-panel">
        <div class="brand-mark">B</div>
        <div class="brand-copy">
            <span class="eyebrow">Personal Expense Tracker</span>
            <h1>@yield('headline', 'Bachat')</h1>
            <p>@yield('subheadline', 'Fast, private, glassy and mobile-first.') </p>
        </div>

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
</body>
</html>
