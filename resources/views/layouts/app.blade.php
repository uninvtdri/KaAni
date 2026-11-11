<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KaAni</title>
    <link rel="stylesheet" href="{{ asset('css/kaani.css') }}">
</head>
<body>
<header class="topbar">
    <strong>KaAni</strong>

    @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.advisories.index') }}">Review Advisories</a>
    @else
        <a href="{{ route('officer.dashboard') }}">Dashboard</a>
        <a href="{{ route('officer.advisories.index') }}">My Advisories</a>
    @endif

    <span class="who">{{ auth()->user()->name }} <span class="role">{{ ucfirst(auth()->user()->role) }}</span></span>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button>Logout</button>
    </form>
</header>

<main>
    @if(session('status'))
        <p class="alert">{{ session('status') }}</p>
    @endif
    @yield('content')
</main>
</body>
</html>
