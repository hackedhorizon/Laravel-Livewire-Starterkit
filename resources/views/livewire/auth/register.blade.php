<div>
    @php
        $formFields = [
            'name' => 50,
            'username' => 30,
            'email' => 50,
            'password' => 300,
        ];
        $recaptchaEnabled = config('services.should_have_recaptcha');
        $siteKey = config('services.google_captcha.site_key');
    @endphp

    <form wire:submit="register"
          class="max-w-md mx-auto">

        {{-- Loop through form fields to generate input components --}}
        @foreach ($formFields as $field => $maxlength)
            {{-- Construct the translation key --}}
            @php
                $translationKey = 'validation.attributes.' . $field;
            @endphp
            <x-forms.input id="{{ $field }}"
                           type="{{ $field === 'password' ? 'password' : 'text' }}"
                           maxlength="{{ $maxlength }}"
                           placeholder="{{ ucfirst(__($translationKey)) }}"
                           variable="{{ $field }}" />
        @endforeach

        {{-- Submit button --}}
        <div wire:ignore>
            <x-buttons.primary-button target="register"
                                      translation="{{ __('Register') }}"
                                      class="{{ $recaptchaEnabled ? 'g-recaptcha' : '' }}"
                                      data-sitekey="{{ $siteKey }}"
                                      data-callback='handle'
                                      data-action='register' />
        </div>

        {{-- Recaptcha section --}}
        @if ($recaptchaEnabled)
            {{-- Recaptcha information --}}
            <x-forms.recaptcha />

            {{-- Recaptcha token error --}}
            <x-forms.error attribute='recaptcha' />
        @endif

        {{-- Register error message --}}
        <x-forms.error attribute='register' />

        {{-- Login card with link --}}
        <x-cards.login />

    </form>


    @if ($recaptchaEnabled)
        <script src="https://www.google.com/recaptcha/api.js?render={{ $siteKey }}"></script>
        <script>
            function handle(e) {
                grecaptcha.ready(function() {
                    grecaptcha.execute('{{ $siteKey }}', {
                        action: 'register'
                    }).then(function(token) {
                        @this.set('recaptchaToken', token);
                        @this.register();
                    });
                })
            }
        </script>
    @endif
</div>
