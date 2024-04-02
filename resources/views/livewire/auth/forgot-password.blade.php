<div>
    <form wire:submit.prevent="sendResetPasswordEmailNotification">
        <div>
            <label for="email">Email</label>
            <input type="email" wire:model="email" id="email" placeholder="Email">
            @error('email')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <button type="submit">{{ __('Send') }}</button>
    </form>

    {{-- Reset password error message --}}
    <x-forms.error attribute='reset-password' />
</div>
