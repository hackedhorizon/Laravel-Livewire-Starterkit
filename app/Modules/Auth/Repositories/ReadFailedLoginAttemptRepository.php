<?php

namespace App\Modules\Auth\Repositories;

use App\Models\FailedLoginAttempt;
use App\Modules\Auth\Interfaces\ReadFailedLoginAttemptRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ReadFailedLoginAttemptRepository implements ReadFailedLoginAttemptRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getFailedLoginAttempts(): Collection
    {
        return FailedLoginAttempt::all();
    }

    /**
     * {@inheritdoc}
     */
    public function getFailedLoginAttempt($id): ?FailedLoginAttempt
    {
        return FailedLoginAttempt::find($id);
    }
}
