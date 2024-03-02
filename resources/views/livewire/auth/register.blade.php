<div>
    @php
        $formFields = ['name' => 50, 'username' => 30, 'email' => 50, 'password' => 300];
    @endphp

    {{-- User registration form --}}
    <form wire:submit="store"
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

            <x-forms.primary-button target="store"
                                    translation="{{ __('Register') }}"
                                    class="g-recaptcha"
                                    data-sitekey="{{ config('services.google_captcha.site_key') }}"
                                    data-callback='handle'
                                    data-action='store' />

        </div>

        {{-- Display recaptcha token error if any --}}
        <x-forms.error attribute='recaptcha' />

        {{-- Display register error message if any --}}
        <x-forms.error attribute='register' />

        <div>
            <p>This site is protected by ReCaptcha and the Google</p>
            <b><a href="https://policies.google.com/privacy">Privacy Policy</a></b> and
            <b><a href="https://policies.google.com/terms">Terms of Service</a></b> apply.
        </div>

    </form>

    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.google_captcha.site_key') }}"></script>
    <script>
        function handle(e) {
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ config('services.google_captcha.site_key') }}', {
                        action: 'store'
                    })
                    .then(function(token) {
                        @this.set('recaptchaToken', token);
                        @this.store()
                    });
            })
        }
    </script>
</div>
