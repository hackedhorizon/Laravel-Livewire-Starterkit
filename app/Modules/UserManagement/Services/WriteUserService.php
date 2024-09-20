<?php

namespace App\Modules\UserManagement\Services;

use App\Models\User;
use App\Modules\UserManagement\DTOs\UserDTO;
use App\Modules\UserManagement\Interfaces\WriteUserServiceInterface;
use App\Modules\UserManagement\Repositories\WriteUserRepository;

class WriteUserService implements WriteUserServiceInterface
{
    private WriteUserRepository $repository;

    public function __construct(WriteUserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function createUser($name, $username, $email, $password): User
    {
        $userDto = new UserDTO;
        $userDto->setName($name);
        $userDto->setUsername($username);
        $userDto->setEmail($email);
        $userDto->setPassword($password);

        return $this->repository->createUser($userDto);
    }

    /**
     * {@inheritdoc}
     */
    public function updateUser($id, $data): bool
    {
        return $this->repository->updateUser($id, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteUser($id): bool
    {
        return $this->repository->deleteUser($id);
    }
}
