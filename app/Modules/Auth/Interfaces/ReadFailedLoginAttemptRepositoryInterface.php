<?php

namespace App\Modules\Auth\Interfaces;

interface ReadFailedLoginAttemptRepositoryInterface
{
    public function getAllFailedLogin();

    public function getFailedLoginById($id);
}
