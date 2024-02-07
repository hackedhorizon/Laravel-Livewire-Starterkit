<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Page Title' }}</title>
    @vite('resources/js/app.js')
</head>

<body>
    <nav>
        @if (Auth::check())
            Welcome, <strong> {{ Auth::user()->name }} </strong> <br>
        @endif
        <a href="{{ route('home') }}" wire:navigate>Főoldal</a>
        <a href="{{ route('register') }}" wire:navigate>Regisztráció</a>
        <a href="{{ route('login') }}" wire:navigate>Bejelentkezés</a>
    </nav>

    {{ $slot }}
</body>

</html>
