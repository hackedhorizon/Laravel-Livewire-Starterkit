<div {{ $attributes->merge(['class' => 'text-red-500']) }}>
    @error($attribute)
        <p>{{ $message }}</p>
    @enderror
</div>
