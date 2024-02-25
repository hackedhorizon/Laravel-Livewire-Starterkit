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
        <label>
            <button class="mt-2"
                    wire:loading.attr="disabled"
                    wire:target="login">{{ __('Login') }}
            </button>
        </label>

        {{-- Display login error message if any --}}
        @error('login')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </form>
</div>
