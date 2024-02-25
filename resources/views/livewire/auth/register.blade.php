<div>
    {{-- User registration form --}}
    <form wire:submit="store"
          class="max-w-md mx-auto">

        {{-- Loop through form fields to generate input components --}}
        @foreach (['name' => 50, 'username' => 30, 'email' => 50, 'password' => 300] as $field => $maxlength)
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
        <x-forms.primary-button target="store"
                                translation="{{ __('Register') }}" />

        {{-- Display registration error message if any --}}
        @error('registration')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </form>
</div>
