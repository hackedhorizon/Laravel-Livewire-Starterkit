<div {{ $attributes->merge(['class' => '']) }}>
    <p>{{ __('Forgot your password?') }}</p>
    <a href="{{ route('password.request') }}">{{ __('Reset Password') }}</a>
</div>
