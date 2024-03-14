<?php

namespace App\Modules\Registration\Interfaces;

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
     * @return User Created user.
     */
    public function registerUser(string $name, string $username, string $email, string $password): User;
}
