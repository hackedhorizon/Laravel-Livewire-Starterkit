<form wire:model.change='selectedLanguage' class="inline-block">
    <select name="language" id="language">
        <option value="null" disabled>{{ __('actions.select_a_language') }}</option>
        @foreach ($languages as $key => $language)
            <option wire:key="{{ $key }}" value="{{ $key }}"
                @if ($key === $selectedLanguage) selected @endif>{{ $language }}</option>
        @endforeach
    </select>

    {{ $selectedLanguage }}
</form>
