<?php

namespace App\Livewire\Features;

use App\Modules\Localization\Services\LocalizationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Livewire\Component;

class LanguageSwitcher extends Component
{
    public $selectedLanguage = '';

    public $languages = '';

    public function render()
    {
        return view('livewire.features.language-switcher');
    }

    public function updatedSelectedLanguage(LocalizationService $localizationService)
    {
        session()->put('locale', $this->selectedLanguage);

        if (Auth::check()) {
            $userId = Auth::user()->id;

            $localizationService->setUserLanguage($userId, $this->selectedLanguage);
        }

        $localizationService->setCurrentLanguage($this->selectedLanguage);

        return $this->redirect('/', navigate: true);
    }

    public function mount()
    {
        $this->selectedLanguage = session()->get('locale', Config::get('app.fallback_locale'));
        $this->languages = collect(Config::get('app.locales'));
    }
}
