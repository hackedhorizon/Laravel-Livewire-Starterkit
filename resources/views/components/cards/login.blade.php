<div {{ $attributes->merge(['class' => '']) }}>
    <p>{{ __('Already have an account?') }}</p>
    <a href="{{ route('login') }}"
       wire:navigate>{{ __('Login') }}</a>

</div>
