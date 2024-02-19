<?php

namespace App\Modules\Auth\Services;

use App\Models\FailedLoginAttempt;
use App\Modules\Auth\Interfaces\ReadFailedLoginAttemptServiceInterface;
use App\Modules\Auth\Repositories\ReadFailedLoginAttemptRepository;
use Illuminate\Database\Eloquent\Collection;

class ReadFailedLoginAttemptService implements ReadFailedLoginAttemptServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public ReadFailedLoginAttemptRepository $repository;

    public function __construct(ReadFailedLoginAttemptRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function getFailedLoginAttempts(): Collection
    {
        return $this->repository->getFailedLoginAttempts();
    }

    /**
     * {@inheritdoc}
     */
    public function getFailedLoginAttempt(string $id): ?FailedLoginAttempt
    {
        return $this->repository->getFailedLoginAttempt($id);
    }
}
