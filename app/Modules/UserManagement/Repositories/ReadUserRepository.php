<?php

namespace App\Modules\UserManagement\Repositories;

use App\Models\User;
use App\Modules\UserManagement\Interfaces\ReadUserRepositoryInterface;

class ReadUserRepository implements ReadUserRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findByUsernameOrEmail($identifier): ?User
    {
        return User::where('username', $identifier)
            ->orWhere('email', $identifier)
            ->first();
    }

    /**
     * {@inheritdoc}
     */
    public function getUserLanguage(int $userId): string
    {
        $user = User::findOrFail($userId);

        return $user->language;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserFullName(int $userId): string
    {
        $user = User::findOrFail($userId);

        return $user->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserNickName(int $userId): string
    {
        $user = User::findOrFail($userId);

        return $user->username;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserEmailAddress(int $userId): string
    {
        $user = User::findOrFail($userId);

        return $user->email;
    }
}
