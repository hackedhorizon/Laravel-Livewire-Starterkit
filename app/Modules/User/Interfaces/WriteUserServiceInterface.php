<?php

namespace App\Modules\User\Interfaces;

use App\Models\User;

/**
 * Interface WriteUserServiceInterface
 *
 * Represents a service for writing user data.
 */
interface WriteUserServiceInterface
{
    /**
     * Create a new user.
     *
     * @param  string  $name  User's name.
     * @param  string  $username  User's username.
     * @param  string  $email  User's email.
     * @param  string  $password  User's password.
     * @return User Created user.
     */
    public function createUser($name, $username, $email, $password): User;

    /**
     * Update an existing user.
     *
     * @param  int  $id  User ID to update.
     * @param  array  $data  Updated user data.
     * @return array|null Updated user data or null if update fails.
     */
    public function updateUser($id, $data): ?User;

    /**
     * Delete a user.
     *
     * @param  int  $id  User ID to delete.
     * @return bool True if deletion is successful, false otherwise.
     */
    public function deleteUser($id): bool;
}
