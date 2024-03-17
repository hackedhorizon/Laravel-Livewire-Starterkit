<?php

namespace App\Modules\Localization\Interfaces;

interface LocalizationServiceInterface
{
    public function getUserLanguage(int $userId): string;

    public function setUserLanguage(int $userId, string $language): bool;

    // Set the language of the application
    public function setCurrentLanguage(string $language): void;
}
