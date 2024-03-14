<?php

namespace App\Modules\Authentication\Interfaces;

use App\Models\FailedLoginAttempt;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface ReadFailedLoginAttemptServiceInterface
 *
 * Represents a repository for reading failed login attempts via a service.
 */
interface ReadFailedLoginAttemptServiceInterface
{
    /**
     * Retrieve all failed login attempts.
     *
     * @return Collection A collection containing details of all failed login attempts.
     */
    public function getFailedLoginAttempts(): Collection;

    /**
     * Retrieve details of a specific failed login attempt by ID.
     *
     * @param  string  $id  The unique identifier of the failed login attempt.
     * @return FailedLoginAttempt|null The specific failed login attempt record or null if not found.
     */
    public function getFailedLoginAttempt(string $id): ?FailedLoginAttempt;
}
