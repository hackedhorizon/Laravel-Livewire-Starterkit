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
        @auth
            Welcome, <strong>{{ Auth::user()->username }}</strong> <br>
            <a href="{{ route('home') }}" wire:navigate>Főoldal</a>
            @livewire('auth.logout')
        @else
            <a href="{{ route('home') }}" wire:navigate>Főoldal</a>
            <a href="{{ route('register') }}" wire:navigate>Regisztráció</a>
            <a href="{{ route('login') }}" wire:navigate>Bejelentkezés</a>
        @endauth
    </nav>

    @if (session('message'))
        <div class="text-white bg-green-500">
            {{ session('message') }}
        </div>
    @endif

    {{ $slot }}
</body>

</html>
