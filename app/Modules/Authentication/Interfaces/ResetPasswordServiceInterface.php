<?php

namespace App\Modules\Authentication\Interfaces;

interface ResetPasswordServiceInterface
{
    public function setCredentials(array $credentials): void;

    public function sendResetPasswordLink(string $email): string;

    public function resetPassword(): string;

    public function addFlashMessage(string $status): void;
}
