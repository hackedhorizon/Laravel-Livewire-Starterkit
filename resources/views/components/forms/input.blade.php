{{--
    General input component for reuse.

    Parameters:
     - id          = unique identifier for the input field
     - type        = type of the input field (email, text, password)
     - maxlength   = max length of the input field (optional)
     - placeholder = text to show as a placeholder
     - variable    = Livewire variable (default is two-way binding via blur)

    Usage:
    <x-forms.input
        id="{{ variable }}"
        type="{{ variable }}"
        ...
    />
--}}

<div class="relative">
    {{-- Input field with or without maxlength attribute --}}
    <input id="{{ $id }}"
           name={{ $id }}
           type="{{ $type }}"
           @if (isset($maxlength)) maxlength="{{ $maxlength }}" @endif
           @if (isset($placeholder)) placeholder="{{ $placeholder }}" @endif
           wire:model.blur="{{ $variable }}"
           {{ $attributes->merge(['class' => 'w-full p-2 mt-2 border border-gray-300']) }}
           autofocus
           autocomplete="{{ $variable }}">

    {{-- Display character count for input length if maxlength is set --}}
    @if (isset($maxlength))
        <p x-text="$wire.{{ $variable }}.length + '/{{ $maxlength }}'"
           class="absolute inset-y-0 right-0 flex items-center pr-3 mt-2 pointer-events-none">
        </p>
    @endif

    {{-- Display Livewire validation error message --}}
    <x-forms.error attribute='{{ $variable }}' />

</div>
