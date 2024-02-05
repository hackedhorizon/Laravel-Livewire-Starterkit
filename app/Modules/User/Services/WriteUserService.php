<?php

namespace App\Modules\User\Services;

use App\Modules\User\DTOs\UserDTO;
use App\Modules\User\Interfaces\WriteUserServiceInterface;
use App\Modules\User\Repositories\WriteUserRepository;

class WriteUserService implements WriteUserServiceInterface
{
    private $userDto;
    private $repository;

    public function __construct(UserDTO $userDto, WriteUserRepository $repository)
    {
        $this->userDto = $userDto;
        $this->repository = $repository;
    }

    public function createUser($name, $username, $email, $password)
    {
        $this->userDto->setName($name);
        $this->userDto->setUsername($username);
        $this->userDto->setEmail($email);
        $this->userDto->setPassword($password);

        return $this->repository->createUser(array($this->userDto));
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
