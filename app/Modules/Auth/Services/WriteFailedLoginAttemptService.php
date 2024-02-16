<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\DTOs\LoginAttemptDTO;
use App\Modules\Auth\Interfaces\WriteFailedLoginAttemptServiceInterface;
use App\Modules\Auth\Repositories\WriteFailedLoginAttemptRepository;

class WriteFailedLoginAttemptService implements WriteFailedLoginAttemptServiceInterface
{
    public WriteFailedLoginAttemptRepository $repository;

    public function __construct(WriteFailedLoginAttemptRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createFailedLoginAttempt(string $user_id, string $email_address, string $ip_address)
    {
        $loginAttemptDTO = new LoginAttemptDTO(
            $user_id, $email_address, $ip_address
        );

        $this->repository->createFailedLoginAttempt($loginAttemptDTO);
    }

    public function deleteFailedLoginAttempt(string $id)
    {

    }
}
