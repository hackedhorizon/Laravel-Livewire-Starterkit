{{--
    This is the general input component.
    You can reuse this component everywhere.
    It's recommended to use with a foreach.

    Parameters:
     - id          = unique identifier for the input field
     - type        = type of the input field (email, text, password)
     - maxlength   = max length of the input field (optional)
     - placeholder = what text to show for user
     - variable    = livewire variable (the default is two-way binding via blur)

    Usage:
    <x-forms.input
        id="{{ variable }}"
        type="{{ variable }}"
        ...
    />
--}}

<div>
    <div class="relative">
        @if (isset($maxlength))
            <input id={{ $id }} type="{{ $type }}" maxlength="{{ $maxlength }}"
                placeholder="{{ $placeholder }}" wire:model.blur="{{ $variable }}"
                class="w-full p-2 mt-2 border border-gray-300">
            <p x-text="$wire.{{ $variable }}.length + '/{{ $maxlength }}'"
                class="absolute inset-y-0 right-0 flex items-center pr-3 mt-2 pointer-events-none">
            </p>
        @else
            <input id={{ $id }} type="{{ $type }}" placeholder="{{ $placeholder }}"
                wire:model.blur="{{ $variable }}" class="w-full p-2 mt-2 border border-gray-300">
        @endif
    </div>
    @error($variable)
        <em class="text-red-600">{{ $message }}</em>
        <br>
    @enderror
</div>
