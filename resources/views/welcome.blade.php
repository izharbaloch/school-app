<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body class="nativephp-safe-area">
    <h1>Welcome to Laravel</h1>
    {{-- login and registration links --}}
    @if(auth()->check())
        <p>Welcome, {{ auth()->user()->name }}</p>
        <a href="{{ route('logout') }}">Logout</a>
    @else
        <a href="{{ route('login') }}">Login</a>
        <a href="{{ route('register') }}">Register</a>
    @endif
</body>
</html>
