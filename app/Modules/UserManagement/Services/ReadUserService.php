<?php

namespace App\Modules\UserManagement\Services;

use App\Models\User;
use App\Modules\UserManagement\Interfaces\ReadUserServiceInterface;
use App\Modules\UserManagement\Repositories\ReadUserRepository;
use Illuminate\Database\Eloquent\Collection;

class ReadUserService implements ReadUserServiceInterface
{
    public $repository;

    public function __construct(ReadUserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsers(): Collection
    {
        return $this->repository->getAllUsers();
    }

    /**
     * {@inheritdoc}
     */
    public function findUserById($id): ?User
    {
        return $this->repository->findUserByID($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByUsernameOrEmail($identifier): ?User
    {
        return $this->repository->findByUsernameOrEmail($identifier);
    }
}
