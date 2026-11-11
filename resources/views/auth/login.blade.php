<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - KaAni</title>
    <link rel="stylesheet" href="{{ asset('css/kaani.css') }}">
</head>
<body class="login-page">
<div class="login-wrap">
    <div class="login-brand">
        <h1>KaAni</h1>
        <p>AI-Powered Crop Advisory and Farm Decision Support System for Cagayan Province</p>
        <ul>
            <li>Live weather per municipality</li>
            <li>Automatic crop risk levels</li>
            <li>AI-drafted advisories with admin approval</li>
        </ul>

        <svg class="hills" viewBox="0 0 400 120" preserveAspectRatio="none">
            <path d="M0 70 Q100 30 200 60 T400 50 V120 H0Z" fill="rgba(255,255,255,.08)"/>
            <path d="M0 90 Q120 55 240 85 T400 75 V120 H0Z" fill="rgba(255,255,255,.12)"/>
            <path d="M0 105 Q140 85 280 102 T400 98 V120 H0Z" fill="rgba(224,176,122,.35)"/>
        </svg>
    </div>

    <div class="login-form">
        <h2>Welcome</h2>
        <p class="sub">Log in to your KaAni account</p>

        @if($errors->any())
            <p class="alert error">{{ $errors->first() }}</p>
        @endif

        <form method="POST" action="{{ url('/login') }}" novalidate>
            @csrf
            <label>Email</label>
            <div class="field">
                <input type="email" name="email" value="{{ old('email') }}" placeholder="you@kaani.test">
            </div>

            <label>Password</label>
            <div class="field">
                <input type="password" name="password" id="password" placeholder="Enter your password">
                <button type="button" class="show" onclick="togglePassword(this)">Show</button>
            </div>

            <button class="submit">Log in →</button>
        </form>
        <small>Admin: admin@kaani.test Officer: officer@kaani.test</small>
    </div>
</div>

<script>
    function togglePassword(btn) {
        const input = document.getElementById('password');
        const hidden = input.type === 'password';
        input.type = hidden ? 'text' : 'password';
        btn.textContent = hidden ? 'Hide' : 'Show';
    }
</script>
</body>
</html>
