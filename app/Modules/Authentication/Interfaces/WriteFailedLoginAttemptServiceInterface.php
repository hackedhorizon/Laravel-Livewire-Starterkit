<?php

namespace App\Modules\Authentication\Interfaces;

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
}
