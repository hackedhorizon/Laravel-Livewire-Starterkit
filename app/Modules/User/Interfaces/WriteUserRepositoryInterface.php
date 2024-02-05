<?php

namespace App\Modules\User\Interfaces;

/**
 * Interface WriteUserRepositoryInterface
 *
 * Represents a repository for writing (creating, updating, deleting) user data.
 */

interface WriteUserRepositoryInterface
{
    /**
     * Create a new user.
     *
     * @param array $data User data for creation.
     *
     * @return \App\Models\User|null Created user instance or null if creation fails.
     */
    public function createUser($data);

    /**
     * Update an existing user.
     *
     * @param int   $id   User ID to update.
     * @param array $data Updated user data.
     *
     * @return \App\Models\User|null Updated user instance or null if update fails.
     */
    public function updateUser($id, $data);

    /**
     * Delete a user.
     *
     * @param int $id User ID to delete.
     *
     * @return bool True if deletion is successful, false otherwise.
     */
    public function deleteUser($id);
}
