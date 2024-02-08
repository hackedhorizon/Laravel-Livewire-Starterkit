<div>
    <form wire:submit='store' class="max-w-md mx-auto">

        @foreach (['name' => 50, 'username' => 30, 'email' => 50, 'password' => 300] as $field => $maxlength)
            <x-forms.input id="{{ $field }}" type="{{ $field === 'password' ? 'password' : 'text' }}"
                maxlength="{{ $maxlength }}" placeholder="{{ ucfirst($field) }}" variable="{{ $field }}" />
        @endforeach

        <label>
            <button class="mt-2" wire:loading.attr="disabled" wire:target="store">Regisztráció</button>
        </label>
    </form>
</div>
