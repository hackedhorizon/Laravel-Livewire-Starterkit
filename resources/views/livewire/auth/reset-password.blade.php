<div>
    <form wire:submit.prevent="resetPassword">
        <input type="hidden" wire:model="token">
        <input type="hidden" wire:model="email">
        <div>
            <label for="password">{{ ucfirst(__('validation.attributes.password')) }}</label>
            <input type="password" wire:model="password" id="password" placeholder="Password">
            @error('password')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label for="password_confirmation">{{ __('Confirm Password') }}</label>
            <input type="password" wire:model="password_confirmation" id="password_confirmation"
                placeholder="Confirm Password">
        </div>
        <button type="submit">Reset Password</button>
    </form>
    @if ($status)
        <div>{{ $status }}</div>
    @endif
</div>
