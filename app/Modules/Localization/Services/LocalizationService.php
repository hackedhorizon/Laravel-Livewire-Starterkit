<?php

namespace App\Modules\Localization\Services;

use App\Modules\Localization\Interfaces\LocalizationServiceInterface;
use App\Modules\UserManagement\Repositories\ReadUserRepository;
use App\Modules\UserManagement\Repositories\WriteUserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class LocalizationService implements LocalizationServiceInterface
{
    private WriteUserRepository $writeUserRepository;

    private ReadUserRepository $readUserRepository;

    public function __construct(
        WriteUserRepository $writeUserRepository,
        ReadUserRepository $readUserRepository
    ) {
        $this->writeUserRepository = $writeUserRepository;
        $this->readUserRepository = $readUserRepository;
    }

    public function updateCurrentlySelectedLanguage(?int $userId, string $language): bool
    {
        // If the user logged in, update the user's language in the database
        if ($userId !== null) {
            return $this->writeUserRepository->setUserLanguage($userId, $language);
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
        $userLanguage = $this->readUserRepository->getUserLanguage($userId);

        return $userLanguage ?? Config::get('app.locale');
    }

    public function setAppLocale($locale): void
    {
        app()->setLocale($locale);
        session()->put('locale', $locale);
    }
}
