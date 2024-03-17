<?php

namespace App\Modules\Authentication\Interfaces;

use App\Models\FailedLoginAttempt;
use App\Modules\Authentication\DTOs\LoginAttemptDTO;

/**
 * Interface ReadFailedLoginAttemptServiceInterface
 *
 * Represents a repository for writing (creating and deleting) failed login attempts to the repository.
 */
interface WriteFailedLoginAttemptRepositoryInterface
{
    /**
     * Create a new failed login attempt record.
     *
     * @param  LoginAttemptDTO  $login_credentials  The DTO containing login attempt details.
     * @return FailedLoginAttempt|null The created FailedLoginAttempt or null if creation failed.
     */
    public function createFailedLoginAttempt(LoginAttemptDTO $login_credentials): ?FailedLoginAttempt;

    /**
     * Delete a specific failed login attempt record by ID.
     *
     * @param  string  $id  The unique identifier of the failed login attempt to be deleted.
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function deleteFailedLoginAttempt(string $id): bool;
}
