<?php

namespace App\Modules\Localization\Services;

use App\Modules\Localization\Interfaces\LocalizationServiceInterface;
use App\Modules\UserManagement\Services\ReadUserService;
use App\Modules\UserManagement\Services\WriteUserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class LocalizationService implements LocalizationServiceInterface
{
    private WriteUserService $writeUserService;

    private ReadUserService $readUserService;

    public function __construct(
        WriteUserService $writeUserService,
        ReadUserService $readUserService
    ) {
        $this->writeUserService = $writeUserService;
        $this->readUserService = $readUserService;
    }

    public function updateCurrentlySelectedLanguage(?int $userId, string $language): bool
    {
        // If the user logged in, update the user's language in the database
        if ($userId !== null) {
            return $this->writeUserService->updateUser($userId, [
                'language' => $language,
            ]);
        }

        // Handle scenario where user is not logged in, store the language preference in session
        session()->put('locale', $language);

        return true;
    }

    public function getAppLocale(): string
    {
        return Auth::check()
            ? $this->getUserLocale(Auth::id())
            : session('locale', Config::get('app.locale'));
    }

    private function getUserLocale($userId): string
    {
        $userLanguage = $this->readUserService->getUserProperty('language', $userId);

        return $userLanguage ?? Config::get('app.locale');
    }

    public function setAppLocale($locale): void
    {
        app()->setLocale($locale);
        session()->put('locale', $locale);
    }
}
