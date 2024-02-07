<?php

namespace App\Modules\User\Services;

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

    public function createUser($name, $username, $email, $password)
    {
        $userDto = new UserDTO($name, $username, $email, $password);

        return $this->repository->createUser($userDto);
    }

    public function updateUser($id, $data)
    {
        return $this->repository->updateUser($id, $data);
    }

    public function deleteUser($id)
    {
        return $this->repository->deleteUser($id);
    }
}
