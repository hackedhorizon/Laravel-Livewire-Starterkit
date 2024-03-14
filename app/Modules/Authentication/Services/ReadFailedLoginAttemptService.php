<?php

namespace App\Modules\Authentication\Services;

use App\Models\FailedLoginAttempt;
use App\Modules\Authentication\Interfaces\ReadFailedLoginAttemptServiceInterface;
use App\Modules\Authentication\Repositories\ReadFailedLoginAttemptRepository;
use Illuminate\Database\Eloquent\Collection;

class ReadFailedLoginAttemptService implements ReadFailedLoginAttemptServiceInterface
{
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
