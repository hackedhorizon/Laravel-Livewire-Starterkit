<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\DTOs\LoginAttemptDTO;
use App\Modules\Auth\Interfaces\WriteFailedLoginAttemptServiceInterface;
use App\Modules\Auth\Repositories\WriteFailedLoginAttemptRepository;

/**
 * Service class for writing (creating and deleting) failed login attempts.
 */
class WriteFailedLoginAttemptService implements WriteFailedLoginAttemptServiceInterface
{
    /**
     * The repository for interacting with the database.
     */
    public WriteFailedLoginAttemptRepository $repository;

    /**
     * Create a new WriteFailedLoginAttemptService instance.
     *
     * @param  WriteFailedLoginAttemptRepository  $repository  The repository for failed login attempts.
     */
    public function __construct(WriteFailedLoginAttemptRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Create a new failed login attempt record.
     *
     * @param  string  $user_id  The user ID associated with the failed login attempt.
     * @param  string  $email_address  The email address used for the login attempt.
     * @param  string  $ip_address  The IP address from which the attempt was made.
     */
    public function createFailedLoginAttempt(string $user_id, string $email_address, string $ip_address): void
    {
        // Create a LoginAttemptDTO for the new attempt
        $loginAttemptDTO = new LoginAttemptDTO(
            $user_id, $email_address, $ip_address
        );

        // Call the repository method to create the failed login attempt
        $this->repository->createFailedLoginAttempt($loginAttemptDTO);
    }

    /**
     * Delete a specific failed login attempt record by ID.
     *
     * @param  string  $id  The unique identifier of the failed login attempt to be deleted.
     */
    public function deleteFailedLoginAttempt(string $id): void
    {
        // TODO: Implement deleteFailedLoginAttempt method
    }
}
