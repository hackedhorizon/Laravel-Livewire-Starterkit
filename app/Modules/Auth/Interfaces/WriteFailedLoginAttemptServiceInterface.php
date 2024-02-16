<?php

namespace App\Modules\Auth\Interfaces;

interface WriteFailedLoginAttemptServiceInterface
{
    public function createFailedLoginAttempt(string $user_id, string $email_address, string $ip_address);

    public function deleteFailedLoginAttempt(string $id);
}
