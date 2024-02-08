<?php

namespace App\Modules\User\Repositories;

use App\Models\User;
use App\Modules\User\Interfaces\ReadUserRepositoryInterface;

class ReadUserRepository implements ReadUserRepositoryInterface
{
    /**
     * Get all users.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllUsers()
    {
        return User::all();
    }

    /**
     * Get a user by their ID.
     *
     * @param  int  $id  User ID.
     * @return \App\Models\User|null
     */
    public function getUserById($id)
    {
        return User::find($id);
    }

    /**
     * Find a user via it's username or email.
     *
     * @param  string  $identifier
     * @return \App\Models\User|null
     */
    public function findByUsernameOrEmail($identifier)
    {
        return User::where('username', $identifier)
            ->orWhere('email', $identifier)
            ->first();
    }
}
