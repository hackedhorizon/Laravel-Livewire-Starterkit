<?php

namespace App\Modules\Auth\Interfaces;

use App\Modules\Auth\DTOs\LoginAttemptDTO;

interface WriteFailedLoginAttemptRepositoryInterface
{
    public function createFailedLoginAttempt(LoginAttemptDTO $login_credentials);

    public function deleteFailedLoginAttempt(string $id);
}
