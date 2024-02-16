<?php

namespace App\Modules\Auth\Interfaces;

interface ReadFailedLoginAttemptServiceInterface
{
    public function getFailedLoginAttempts();

    public function getFailedLoginAttempt(string $id);
}
