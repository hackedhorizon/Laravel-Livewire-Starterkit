<div>
    <form wire:submit.prevent="sendResetPasswordEmailNotification">
        <div>
            <label for="email">Email</label>
            <input type="email"
                   wire:model="email"
                   id="email"
                   placeholder="Email">
            @error('email')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <button type="submit">Reset Password</button>
    </form>
    @if ($status)
        <div>{{ $status }}</div>
    @endif
</div>
