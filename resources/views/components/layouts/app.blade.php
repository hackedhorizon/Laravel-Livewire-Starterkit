<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Page Title' }}</title>
    @vite('resources/js/app.js')
</head>

<body>

    {{-- Email notification --}}
    <x-notifications.verify-email-notification />

    {{-- Navbar --}}
    @livewire('features.navbar')

    {{-- Session messages --}}
    @session('message_success')
        <div class="text-white bg-green-500">
            {{ $value }}
        </div>
    @endsession

    @session('message_failed')
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
