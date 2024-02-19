<?php

namespace App\Modules\Auth\Interfaces;

use App\Models\FailedLoginAttempt;

/**
 * Interface ReadFailedLoginAttemptServiceInterface
 *
 * Represents a repository for writing (creating and deleting) failed login attempts via a service.
 */
interface WriteFailedLoginAttemptServiceInterface
{
    /**
     * Create a new failed login attempt record.
     *
     * @param  string  $user_id  The user ID associated with the failed login attempt.
     * @param  string  $email_address  The email address used for the login attempt.
     * @param  string  $ip_address  The IP address from which the attempt was made.
     * @return FailedLoginAttempt|null The created FailedLoginAttempt or null if creation failed.
     */
    public function createFailedLoginAttempt(string $user_id, string $email_address, string $ip_address): ?FailedLoginAttempt;

    /**
     * Delete a specific failed login attempt record by ID.
     *
     * @param  string  $id  The unique identifier of the failed login attempt to be deleted.
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function deleteFailedLoginAttempt(string $id): bool;
}
