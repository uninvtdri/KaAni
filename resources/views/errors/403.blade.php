<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Access denied - KaAni</title>
    <link rel="stylesheet" href="{{ asset('css/kaani.css') }}">
</head>
<body class="login-page">
<div class="box denied">
    <h1>403</h1>
    <h2>Access denied</h2>
    <p>{{ $exception->getMessage() ?: 'You are not allowed to open this page.' }}</p>
    <a href="{{ url('/') }}">Go back to my dashboard</a>
</div>
</body>
</html>
