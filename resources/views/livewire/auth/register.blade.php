<div>

    <form wire:submit='store' class="max-w-md mx-auto">

        <div class="relative">
            <input type="text" wire:model='name' maxlength="10" class="w-full p-2 pr-10 border border-gray-300">
            <p x-text="$wire.name.length + '/10'"
                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            </p>
        </div>

        <div class="relative">
            <input type="text" wire:model='username' maxlength="10" class="w-full p-2 mt-2 border border-gray-300">
            <p x-text="$wire.username.length + '/10'"
                class="absolute inset-y-0 right-0 flex items-center pr-3 mt-2 pointer-events-none">
            </p>
        </div>

        <div class="relative">
            <input type="email" wire:model='email' maxlength="10" class="w-full p-2 mt-2 border border-gray-300">
            <p x-text="$wire.email.length + '/10'"
                class="absolute inset-y-0 right-0 flex items-center pr-3 mt-2 pointer-events-none">
            </p>
        </div>

        <div class="relative">
            <input type="password" wire:model='password' maxlength="10" class="w-full p-2 mt-2 border border-gray-300">
            <p x-text="$wire.password.length + '/10'"
                class="absolute inset-y-0 right-0 flex items-center pr-3 mt-2 pointer-events-none">
            </p>
        </div>

        <button class="mt-2">Regisztráció</button>
    </form>



</div>
