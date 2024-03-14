<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Page Title' }}</title>
    @vite('resources/js/app.js')
</head>

<body>
    <x-notifications.verify-email-notification />

    {{-- Navbar --}}

    <nav>
        @auth
            <strong>{{ __('register.welcome') }}, {{ Auth::user()->username }}!</strong> <br>
            <a href="{{ route('home') }}" wire:navigate>{{ __('Home') }}</a>
            @livewire('auth.logout')
        @else
            <a href="{{ route('home') }}" wire:navigate>{{ __('Home') }}</a>
            <a href="{{ route('register') }}" wire:navigate>{{ __('Register') }}</a>
            <a href="{{ route('login') }}" wire:navigate>{{ __('Login') }}</a>
        @endauth
    </nav>


    {{-- Session messages --}}

    @session('message_success')
        <div class="text-white bg-green-500">
            {{ $value }}
        </div>
    @endsession

    {{-- Body --}}

    <main>
        {{ $slot }}
    </main>

</body>

</html>
