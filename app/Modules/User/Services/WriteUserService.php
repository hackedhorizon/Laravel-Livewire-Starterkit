<?php

namespace App\Modules\User\Services;

use App\Models\User;
use App\Modules\User\DTOs\UserDTO;
use App\Modules\User\Interfaces\WriteUserServiceInterface;
use App\Modules\User\Repositories\WriteUserRepository;

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
        $userDto = new UserDTO($name, $username, $email, $password);

        return $this->repository->createUser($userDto);
    }

    /**
     * {@inheritdoc}
     */
    public function updateUser($id, $data): ?User
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
