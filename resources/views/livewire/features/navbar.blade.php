<nav>
    <x-localization.language-switcher />

    @auth
        <strong>{{ __('register.welcome') }}, {{ Auth::user()->username }}!</strong> <br>
        <a href="{{ route('home') }}" wire:navigate>{{ __('Home') }}</a>
        <a href="{{ route('profile') }}" wire:navigate>{{ __('Account') }}</a>
        @livewire('auth.logout')
    @else
        <a href="{{ route('home') }}" wire:navigate>{{ __('Home') }}</a>
        <a href="{{ route('login') }}" wire:navigate>{{ __('Login') }}</a>
    @endauth

</nav>
