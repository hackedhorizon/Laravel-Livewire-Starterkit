<?php

namespace App\Modules\Session\Interfaces;

interface MessageServiceInterface
{
    public function addSuccessMessage(string $message): void;

    public function addErrorMessage(string $message): void;
}
