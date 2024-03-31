<?php

namespace App\Modules\Authentication\Services;

use App\Models\FailedLoginAttempt;
use App\Modules\Authentication\DTOs\LoginAttemptDTO;
use App\Modules\Authentication\Interfaces\WriteFailedLoginAttemptServiceInterface;
use App\Modules\Authentication\Repositories\WriteFailedLoginAttemptRepository;

class WriteFailedLoginAttemptService implements WriteFailedLoginAttemptServiceInterface
{
    public WriteFailedLoginAttemptRepository $repository;

    public function __construct(WriteFailedLoginAttemptRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function createFailedLoginAttempt(string $user_id, string $email_address, string $ip_address): ?FailedLoginAttempt
    {
        // Create a LoginAttemptDTO for the new attempt
        $loginAttemptDTO = new LoginAttemptDTO(
            $user_id,
            $email_address,
            $ip_address
        );

        // Call the repository method to create the failed login attempt
        return $this->repository->createFailedLoginAttempt($loginAttemptDTO);
    }
}
