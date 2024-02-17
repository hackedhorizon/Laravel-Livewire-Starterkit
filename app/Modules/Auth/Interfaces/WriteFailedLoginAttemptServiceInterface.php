<?php

namespace App\Modules\Auth\Interfaces;

/**
 * Interface for writing (creating and deleting) failed login attempts via a service.
 */
interface WriteFailedLoginAttemptServiceInterface
{
    /**
     * Create a new failed login attempt record.
     *
     * @param  string  $user_id  The user ID associated with the failed login attempt.
     * @param  string  $email_address  The email address used for the login attempt.
     * @param  string  $ip_address  The IP address from which the attempt was made.
     * @return void
     */
    public function createFailedLoginAttempt(string $user_id, string $email_address, string $ip_address);

    /**
     * Delete a specific failed login attempt record by ID.
     *
     * @param  string  $id  The unique identifier of the failed login attempt to be deleted.
     * @return void
     */
    public function deleteFailedLoginAttempt(string $id);
}
