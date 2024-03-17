<?php

namespace App\Modules\UserManagement\Interfaces;

use App\Models\User;
use App\Modules\UserManagement\DTOs\UserDTO;

/**
 * Interface WriteUserRepositoryInterface
 *
 * Represents a repository for writing (creating, updating, deleting) user data.
 */
interface WriteUserRepositoryInterface
{
    /**
     * Create a new user in the database.
     *
     * @param  UserDTO  $userDataObject  User DTO for creation.
     * @return \App\Models\User|null Created user instance or null if creation fails.
     */
    public function createUser(UserDTO $userDataObject): ?User;

    /**
     * Update an existing user.
     *
     * @param  int  $id  User ID to update.
     * @param  array  $data  Updated user data.
     * @return \App\Models\User|null Updated user instance or null if update fails.
     */
    public function updateUser($id, $data): ?User;

    /**
     * Delete a user.
     *
     * @param  int  $id  User ID to delete.
     * @return bool True if deletion is successful, false otherwise.
     */
    public function deleteUser($id): bool;

    /**
     * Set a user's preferred language.
     *
     * @param  int  $userId  The user id for searching the user
     * @param  string  $language  The preferred language to set
     */
    public function setUserLanguage(int $userId, string $language): bool;
}
