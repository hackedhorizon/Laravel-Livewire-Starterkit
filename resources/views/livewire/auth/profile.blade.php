@php
    $formFields = [
        'name' => 50,
        'username' => 30,
        'email' => 50,
        'password' => 300,
    ];
@endphp

<div>
    <h1>{{ __('Account data') }}</h1>

    <form wire:submit="updateProfileInformation"
          class="max-w-md mx-auto">

        @foreach ($formFields as $field => $maxlength)
            @php
                $translationKey = 'validation.attributes.' . $field;
                $type = $field === 'password' ? 'password' : 'text';
            @endphp

            <label for="{{ $field }}">{{ ucfirst(__($translationKey)) }}</label>

            <x-forms.input :id="$field"
                           :type="$type"
                           :maxlength="$maxlength"
                           :placeholder="ucfirst(__($translationKey))"
                           :variable="$field"
                           :label="ucfirst(__($translationKey))" />
        @endforeach

        <x-buttons.primary-button translation="{{ __('Update') }}" />

        <button wire:click.prevent="deleteUser"
                wire:confirm="{{ __('profile.are_you_sure_you_want_to_delete_your_account') }}">
            {{ __('profile.delete_user_account') }}
        </button>

        <x-forms.error attribute='update' />
    </form>

</div>
