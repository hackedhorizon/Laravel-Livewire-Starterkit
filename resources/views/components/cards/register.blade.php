<div {{ $attributes->merge(['class' => '']) }}>
    <p>{{ __('Dont have an account yet?') }}</p>
    <a href="{{ route('register') }}"
       wire:navigate>{{ __('Register') }}</a>
</div>
