<?php

namespace App\Modules\User\Services;

use App\Modules\User\Interfaces\ReadUserServiceInterface;
use App\Modules\User\Repositories\ReadUserRepository;

class ReadUserService implements ReadUserServiceInterface
{
    public $repository;

    public function __construct(ReadUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getUsers()
    {
        return $this->repository->getAllUsers();
    }

    public function getUserById($id)
    {
        return $this->repository->getUserByID($id);
    }
}
