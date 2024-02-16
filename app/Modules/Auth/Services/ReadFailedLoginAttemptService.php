<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Interfaces\ReadFailedLoginAttemptServiceInterface;
use App\Modules\Auth\Repositories\ReadFailedLoginAttemptRepository;

class ReadFailedLoginAttemptService implements ReadFailedLoginAttemptServiceInterface
{
    public ReadFailedLoginAttemptRepository $repository;

    public function __construct(ReadFailedLoginAttemptRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getFailedLoginAttempts()
    {
        return $this->repository->getAllFailedLogin();
    }

    public function getFailedLoginAttempt(string $id)
    {
        return $this->repository->getFailedLoginById($id);
    }
}
