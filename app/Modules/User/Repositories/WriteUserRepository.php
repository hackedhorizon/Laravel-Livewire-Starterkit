<?php

namespace App\Modules\User\Repositories;

use App\Models\User;
use App\Modules\User\DTOs\UserDTO;
use App\Modules\User\Interfaces\WriteUserRepositoryInterface;

class WriteUserRepository implements WriteUserRepositoryInterface
{
    /**
     * Create a new user record in the database.
     *
     * @param  array  $data  The data to create the user.
     * @return \App\Models\User The created user instance.
     */
    public function createUser(UserDTO $userDataObject)
    {
        return User::create([
            'name' => $userDataObject->getName(),
            'username' => $userDataObject->getUserName(),
            'email' => $userDataObject->getEmail(),
            'password' => $userDataObject->getPassword(),
        ]);
    }

    /**
     * Update an existing user record in the database.
     *
     * @param  int  $id  The ID of the user to update.
     * @param  array  $data  The updated data for the user.
     * @return \App\Models\User|null The updated user instance, or null if user not found.
     */
    public function updateUser($id, $data)
    {
        $user = User::find($id);

        if ($user) {
            $user->update($data);
        }

        return $user;
    }

    /**
     * Delete a user record from the database.
     *
     * @param  int  $id  The ID of the user to delete.
     * @return bool True if the user was successfully deleted, false otherwise.
     */
    public function deleteUser($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();

            return true;
        }

        return false; // User not found, deletion unsuccessful.
    }
}
