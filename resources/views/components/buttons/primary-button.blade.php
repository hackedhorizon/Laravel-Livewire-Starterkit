<button {{ $attributes->merge(['class' => '']) }}
        wire:loading.attr="disabled"
        @if (isset($target)) wire:target='{{ $target }}' @endif
        @if (isset($click)) wire:click="{{ $click }}" @endif>

    {{ $translation }}
</button>
