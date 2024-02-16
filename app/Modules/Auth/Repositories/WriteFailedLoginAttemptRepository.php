<?php

namespace App\Modules\Auth\Repositories;

use App\Models\FailedLoginAttempt;
use App\Modules\Auth\DTOs\LoginAttemptDTO;
use App\Modules\Auth\Interfaces\WriteFailedLoginAttemptRepositoryInterface;

class WriteFailedLoginAttemptRepository implements WriteFailedLoginAttemptRepositoryInterface
{
    public function createFailedLoginAttempt(LoginAttemptDTO $login_attempt_details)
    {
        return FailedLoginAttempt::create([
            'user_id' => $login_attempt_details->getUserId(),
            'email_address' => $login_attempt_details->getEmailAddress(),
            'ip_address' => $login_attempt_details->getIpAddress(),
        ]);
    }

    public function deleteFailedLoginAttempt(string $id)
    {

    }
}
