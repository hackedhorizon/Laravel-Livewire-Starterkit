<?php

namespace App\Modules\User\Interfaces;

use App\Models\User;

/**
 * Interface RegistrationServiceInterface
 *
 * Represents a service for user registration.
 */
interface RegistrationServiceInterface
{
    /**
     * Register a new user.
     *
     * @param  string  $name  The name of the user.
     * @param  string  $username  The username of the user.
     * @param  string  $email  The email of the user.
     * @param  string  $password  The plain text password of the user.
     * @return User|null Created user or null if creation fails.
     */
    public function registerUser(string $name, string $username, string $email, string $password): ?User;

    /**
     * Check if a livewire component is rate-limited.
     * - not strictly related to livewire components, you can use it freely.
     * - the default limit is 10 request / second
     */
    public function checkIfRateLimited(): void;
}
