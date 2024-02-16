<?php

namespace App\Modules\Auth\Repositories;

use App\Models\FailedLoginAttempt;
use App\Modules\Auth\Interfaces\ReadFailedLoginAttemptRepositoryInterface;

class ReadFailedLoginAttemptRepository implements ReadFailedLoginAttemptRepositoryInterface
{
    public function getAllFailedLogin()
    {
        return FailedLoginAttempt::all();
    }

    public function getFailedLoginById($id)
    {
        return FailedLoginAttempt::find($id);
    }
}
