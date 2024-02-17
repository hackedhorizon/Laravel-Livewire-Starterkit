<?php

namespace App\Modules\Auth\Repositories;

use App\Models\FailedLoginAttempt;
use App\Modules\Auth\DTOs\LoginAttemptDTO;
use App\Modules\Auth\Interfaces\WriteFailedLoginAttemptRepositoryInterface;

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

    /**
     * {@inheritdoc}
     */
    public function deleteFailedLoginAttempt(string $id): bool
    {
        return FailedLoginAttempt::find($id)->delete();
    }
}
