<div>
    @php
        $values = ['identifier', 'password'];
    @endphp

    <form wire:submit='login' class="max-w-md mx-auto">

        @foreach (['username / Email', 'password'] as $key => $field)
            <x-forms.input id="{{ $key }}" type="{{ $field === 'password' ? 'password' : 'text' }}"
                placeholder="{{ ucfirst($field) }}" variable="{{ $values[$key] }}" />
        @endforeach

        <label>
            <button class="mt-2" wire:loading.attr="disabled" wire:target="login">Bejelentkezés</button>
        </label>

        @error('authentication')
            <br>
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </form>

</div>
