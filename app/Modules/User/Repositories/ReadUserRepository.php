<?php

namespace App\Modules\User\Repositories;

use App\Models\User;
use App\Modules\User\Interfaces\ReadUserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ReadUserRepository implements ReadUserRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAllUsers(): Collection
    {
        return User::all();
    }

    /**
     * {@inheritdoc}
     */
    public function findUserById($id): ?User
    {
        return User::find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findByUsernameOrEmail($identifier): ?User
    {
        return User::where('username', $identifier)
            ->orWhere('email', $identifier)
            ->first();
    }
}
