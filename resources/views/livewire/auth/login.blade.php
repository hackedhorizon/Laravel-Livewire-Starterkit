<div>
    {{-- Livewire properties representing form fields --}}
    @php
        $livewireProperties = ['identifier', 'password'];
    @endphp

    {{-- User login form --}}
    <form wire:submit='login'
          class="max-w-md mx-auto">

        {{-- Loop through form fields to generate input components --}}
        @foreach ($livewireProperties as $index => $property)
            {{-- Construct the translation key and determine the input type for the current form field --}}
            @php
                $translationKey = 'validation.attributes.' . $property;
                $inputType = $property === 'password' ? 'password' : 'text';
            @endphp

            {{-- Include reusable input component for each form field --}}
            <x-forms.input id="{{ $index }}"
                           type="{{ $inputType }}"
                           placeholder="{{ ucfirst(__($translationKey)) }}"
                           variable="{{ $property }}" />
        @endforeach

        {{-- Submit button with loading state and translated text --}}
        <x-buttons.primary-button target="login"
                                  translation="{{ __('Login') }}" />

        {{-- Remember me checkbox --}}
        <x-forms.remember-me />

        {{-- Register card with link --}}
        <x-cards.register />

        {{-- Forgot password --}}
        <x-cards.forgot-password />

        {{-- Display login error message if any --}}
        <x-forms.error attribute="login" />
    </form>
</div>
