<?php

namespace App\Modules\Auth\Services;

use App\Models\FailedLoginAttempt;
use App\Modules\Auth\Interfaces\ReadFailedLoginAttemptServiceInterface;
use App\Modules\Auth\Repositories\ReadFailedLoginAttemptRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * Service class for reading failed login attempts.
 */
class ReadFailedLoginAttemptService implements ReadFailedLoginAttemptServiceInterface
{
    /**
     * The repository for interacting with the database.
     */
    public ReadFailedLoginAttemptRepository $repository;

    /**
     * Create a new ReadFailedLoginAttemptService instance.
     *
     * @param  ReadFailedLoginAttemptRepository  $repository  The repository for failed login attempts.
     */
    public function __construct(ReadFailedLoginAttemptRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Retrieve all failed login attempts.
     *
     * @return Collection A collection containing details of all failed login attempts.
     */
    public function getFailedLoginAttempts(): Collection
    {
        return $this->repository->getFailedLoginAttempts();
    }

    /**
     * Retrieve details of a specific failed login attempt by ID.
     *
     * @param  string  $id  The unique identifier of the failed login attempt.
     * @return FailedLoginAttempt|null The specific failed login attempt record or null if not found.
     */
    public function getFailedLoginAttempt(string $id): ?FailedLoginAttempt
    {
        return $this->repository->getFailedLoginAttempt($id);
    }
}
