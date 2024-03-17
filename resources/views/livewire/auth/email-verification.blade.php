<div>
    <div class="">
        {{ __('register.thanks_for_registering') }} <br>
        {{ __('Please click the button below to verify your email address.') }}
    </div>

    <x-buttons.primary-button click="resendEmailVerification"
                              translation="{{ __('register.resend_verification_email') }}" />
</div>
