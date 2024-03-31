<?php

namespace App\Modules\UserManagement\Services;

use App\Models\User;
use App\Modules\UserManagement\Interfaces\ReadUserServiceInterface;
use App\Modules\UserManagement\Repositories\ReadUserRepository;

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
    public function findUserByUsernameOrEmail($userId): ?User
    {
        return $this->repository->findByUsernameOrEmail($userId);
    }

    public function getUserProperty($propertyName, $userId): string
    {
        switch ($propertyName) {
            case 'name':
                return $this->repository->getUserFullName($userId);
            case 'username':
                return $this->repository->getUserNickName($userId);
            case 'email':
                return $this->repository->getUserEmailAddress($userId);
            case 'language':
                return $this->repository->getUserLanguage($userId);
        }
    }
}
