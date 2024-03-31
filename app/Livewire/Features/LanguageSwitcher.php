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

    public function mount(LocalizationService $localizationService)
    {
        $this->selectedLanguage = $localizationService->getAppLocale();
        $this->languages = collect(Config::get('app.locales'));
    }

    public function updatedSelectedLanguage(LocalizationService $localizationService)
    {
        $localizationService->updateCurrentlySelectedLanguage(Auth::id(), $this->selectedLanguage);
        $localizationService->setAppLocale($this->selectedLanguage);

        return $this->redirect('/', navigate: true);
    }
}
