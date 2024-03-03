<div>
    @php
        $formFields = ['name' => 50, 'username' => 30, 'email' => 50, 'password' => 300];
    @endphp

    {{-- User registration form --}}
    <form wire:submit="register"
          class="max-w-md mx-auto">

        {{-- Loop through form fields to generate input components --}}
        @foreach ($formFields as $field => $maxlength)
            {{-- Construct the translation key --}}
            @php
                $translationKey = 'validation.attributes.' . $field;
            @endphp

            {{-- Include reusable input component for each form field --}}
            <x-forms.input id="{{ $field }}"
                           type="{{ $field === 'password' ? 'password' : 'text' }}"
                           maxlength="{{ $maxlength }}"
                           placeholder="{{ ucfirst(__($translationKey)) }}"
                           variable="{{ $field }}" />
        @endforeach

        {{-- Submit button with loading state and translated text --}}
        <div wire:ignore>

            <x-forms.primary-button target="register"
                                    translation="{{ __('Register') }}"
                                    class="g-recaptcha"
                                    data-sitekey="{{ config('services.google_captcha.site_key') }}"
                                    data-callback='handle'
                                    data-action='register' />

        </div>

        {{-- Display recaptcha information --}}
        <x-forms.recaptcha />

        {{-- Display recaptcha token error if any --}}
        <x-forms.error attribute='recaptcha' />

        {{-- Display register error message if any --}}
        <x-forms.error attribute='register' />

    </form>

    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.google_captcha.site_key') }}"></script>
    <script>
        function handle(e) {
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ config('services.google_captcha.site_key') }}', {
                        action: 'register'
                    })
                    .then(function(token) {
                        @this.set('recaptchaToken', token);
                        @this.register()
                    });
            })
        }
    </script>
</div>
