<?php

namespace App\Modules\Auth\Interfaces;

use App\Models\FailedLoginAttempt;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface for reading failed login attempts from the database.
 */
interface ReadFailedLoginAttemptRepositoryInterface
{
    /**
     * Retrieve all failed login attempts from the database.
     *
     * @return Collection A collection of DTOs containing details of all failed login attempts.
     */
    public function getFailedLoginAttempts(): Collection;

    /**
     * Retrieve details of a failed login attempt by ID from the database.
     *
     * @param  string  $id  The unique identifier of the failed login attempt.
     * @return FailedLoginAttempt|null The specific failed login attempt record or null if not found.
     */
    public function getFailedLoginAttempt(string $id): ?FailedLoginAttempt;
}
