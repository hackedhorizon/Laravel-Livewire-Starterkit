<div>
    <form wire:submit='store' class="max-w-md mx-auto">

        @foreach (['name' => 50, 'username' => 30, 'email' => 50, 'password' => 300] as $field => $maxlength)
            <div>
                <div class="relative">
                    <input id={{ $field }} type="{{ $field === 'password' ? 'password' : 'text' }}"
                        wire:model.blur="{{ $field }}" maxlength="{{ $maxlength }}"
                        placeholder="{{ ucfirst($field) }}" class="w-full p-2 mt-2 border border-gray-300">

                    <p x-text="$wire.{{ $field }}.length + '/{{ $maxlength }}'"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 mt-2 pointer-events-none">
                    </p>

                </div>
                @error($field)
                    <em class="text-red-600">{{ $message }}</em>
                    <br>
                @enderror
            </div>
        @endforeach

        <label>
            <button class="mt-2" wire:loading.attr="disabled" wire:target="store">Regisztráció</button>
        </label>
    </form>
</div>
