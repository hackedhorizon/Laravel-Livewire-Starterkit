<div>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('register.thanks_for_registering') }}
    </div>

    <x-forms.primary-button click="resendEmailVerification" translation="{{ __('Verify Email Address') }}" />
</div>
