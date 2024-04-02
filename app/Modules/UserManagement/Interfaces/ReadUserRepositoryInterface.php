<?php

namespace App\Modules\UserManagement\Interfaces;

use App\Models\User;

/**
 * Interface ReadUserRepositoryInterface
 *
 * Represents a repository for reading user data.
 */
interface ReadUserRepositoryInterface
{
    /**
     * Find a user by their username or email address.
     *
     * @param  string  $identifier  The username or email address to search for.
     * @return User|null The user instance if found, otherwise null.
     */
    public function findByUsernameOrEmail(string $identifier): ?User;

    /**
     * Get the preferred language of a user by providing the user ID.
     *
     * @param  int  $userId  The ID of the user.
     * @return string The preferred language(code) of the user.
     */
    public function getUserLanguage(int $userId): string;

    /**
     * Get the full name of a user by providing the user ID.
     *
     * @param  int  $userId  The ID of the user.
     * @return string The full name of the user.
     */
    public function getUserFullName(int $userId): string;

    /**
     * Get the nickname (username) of a user by providing the user ID.
     *
     * @param  int  $userId  The ID of the user.
     * @return string The nickname (username) of the user.
     */
    public function getUserNickName(int $userId): string;

    /**
     * Get the email address of a user by providing the user ID.
     *
     * @param  int  $userId  The ID of the user.
     * @return string The email address of the user.
     */
    public function getUserEmailAddress(int $userId): string;
}
