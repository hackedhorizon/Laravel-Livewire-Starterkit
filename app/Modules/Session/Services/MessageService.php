<?php

namespace App\Modules\Session\Services;

use App\Modules\Session\Interfaces\MessageServiceInterface;

class MessageService implements MessageServiceInterface
{
    public function addSuccessMessage(string $message): void
    {
        session()->flash('message_success', $message);
    }

    public function addErrorMessage(string $message): void
    {
        session()->flash('message_failed', $message);
    }
}
