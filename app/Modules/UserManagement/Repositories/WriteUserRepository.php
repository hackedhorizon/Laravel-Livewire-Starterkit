<?php

namespace App\Modules\UserManagement\Repositories;

use App\Models\User;
use App\Modules\UserManagement\DTOs\UserDTO;
use App\Modules\UserManagement\Interfaces\WriteUserRepositoryInterface;

class WriteUserRepository implements WriteUserRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createUser(UserDTO $userDataTransferObject): ?User
    {
        return User::create([
            'name' => $userDataTransferObject->getName(),
            'username' => $userDataTransferObject->getUserName(),
            'email' => $userDataTransferObject->getEmail(),
            'password' => $userDataTransferObject->getPassword(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    // Todo: change this to use UserDTO
    public function updateUser($id, $data): bool
    {
        $user = User::find($id);

        if (! $user) {
            return false; // User not found, update unsuccessful.
        }

        $user->update($data);

        return $user->save();
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

        return false;
    }
}
