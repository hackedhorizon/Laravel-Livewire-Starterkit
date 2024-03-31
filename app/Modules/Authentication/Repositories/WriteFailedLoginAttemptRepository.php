<?php

namespace App\Modules\Authentication\Repositories;

use App\Models\FailedLoginAttempt;
use App\Modules\Authentication\DTOs\LoginAttemptDTO;
use App\Modules\Authentication\Interfaces\WriteFailedLoginAttemptRepositoryInterface;

class WriteFailedLoginAttemptRepository implements WriteFailedLoginAttemptRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createFailedLoginAttempt(LoginAttemptDTO $login_attempt_details): ?FailedLoginAttempt
    {
        return FailedLoginAttempt::create([
            'user_id' => $login_attempt_details->getUserId(),
            'email_address' => $login_attempt_details->getEmailAddress(),
            'ip_address' => $login_attempt_details->getIpAddress(),
        ]);
    }
}
