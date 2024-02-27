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
        <x-forms.primary-button target="login"
                                translation="{{ __('Login') }}" />

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember"
                   class="inline-flex items-center">
                <input wire:model.change="remember"
                       id="remember"
                       type="checkbox"
                       class="text-indigo-600 border-gray-300 rounded shadow-sm dark:bg-gray-900 dark:border-gray-700 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                       name="remember">
                <span class="text-sm text-gray-600 ms-2 dark:text-gray-400">{{ __('auth.remember_me') }}</span>
            </label>
        </div>

        {{-- Display login error message if any --}}
        <x-forms.error attribute="login" />
    </form>
</div>
