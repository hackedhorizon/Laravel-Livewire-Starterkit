<?php

namespace App\Modules\User\Repositories;

use App\Models\User;
use App\Modules\User\DTOs\UserDTO;
use App\Modules\User\Interfaces\WriteUserRepositoryInterface;

class WriteUserRepository implements WriteUserRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createUser(UserDTO $userDataObject): ?User
    {
        return User::create([
            'name' => $userDataObject->getName(),
            'username' => $userDataObject->getUserName(),
            'email' => $userDataObject->getEmail(),
            'password' => $userDataObject->getPassword(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function updateUser($id, $data): ?User
    {
        $user = User::find($id);

        if ($user) {
            $user->update($data);
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteUser($id): bool
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();

            return true;
        }

        return false; // User not found, deletion unsuccessful.
    }
}
