<?php

namespace App\Modules\Auth\Interfaces;

use App\Models\FailedLoginAttempt;
use App\Modules\Auth\DTOs\LoginAttemptDTO;

/**
 * Interface for writing (creating and deleting) failed login attempts to the repository.
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
