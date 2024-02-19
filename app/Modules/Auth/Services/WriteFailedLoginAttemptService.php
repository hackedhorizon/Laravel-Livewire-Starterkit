<?php

namespace App\Modules\Auth\Services;

use App\Models\FailedLoginAttempt;
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

    /**
     * {@inheritdoc}
     */
    public function createFailedLoginAttempt(string $user_id, string $email_address, string $ip_address): ?FailedLoginAttempt
    {
        // Create a LoginAttemptDTO for the new attempt
        $loginAttemptDTO = new LoginAttemptDTO(
            $user_id, $email_address, $ip_address
        );

        // Call the repository method to create the failed login attempt
        return $this->repository->createFailedLoginAttempt($loginAttemptDTO);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteFailedLoginAttempt(string $id): bool
    {
        return $this->repository->deleteFailedLoginAttempt($id);
    }
}
