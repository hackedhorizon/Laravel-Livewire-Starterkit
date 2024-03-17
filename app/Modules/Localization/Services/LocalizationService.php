<?php

namespace App\Modules\Localization\Services;

use App\Modules\Localization\Interfaces\LocalizationServiceInterface;
use App\Modules\UserManagement\Repositories\ReadUserRepository;
use App\Modules\UserManagement\Repositories\WriteUserRepository;
use Illuminate\Support\Facades\App;

class LocalizationService implements LocalizationServiceInterface
{
    private ReadUserRepository $readUserRepository;

    private WriteUserRepository $writeUserRepository;

    public function __construct(ReadUserRepository $readUserRepository, WriteUserRepository $writeUserRepository)
    {
        $this->readUserRepository = $readUserRepository;
        $this->writeUserRepository = $writeUserRepository;
    }

    public function getUserLanguage(int $userId): string
    {
        return $this->readUserRepository->getUserLanguage($userId);
    }

    public function setUserLanguage(int $userId, string $language): bool
    {
        return $this->writeUserRepository->setUserLanguage($userId, $language);
    }

    public function setCurrentLanguage(string $language): void
    {
        App::setLocale($language);
    }
}
