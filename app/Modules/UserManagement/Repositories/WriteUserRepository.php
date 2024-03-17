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
    // Todo: change this to use UserDTO
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

    /**
     * {@inheritdoc}
     */
    public function setUserLanguage(int $userId, string $language): bool
    {
        $user = User::findOrFail($userId);
        $user->language = $language;

        return $user->save();
    }
}
