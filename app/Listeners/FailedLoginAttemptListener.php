<?php

namespace App\Listeners;

use App\Modules\Auth\Services\WriteFailedLoginAttemptService;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Log;

class FailedLoginAttemptListener
{
    public $failedLoginAttemptService;

    /**
     * Create the event listener.
     */
    public function __construct(WriteFailedLoginAttemptService $failedLoginAttemptService)
    {
        $this->failedLoginAttemptService = $failedLoginAttemptService;
    }

    /**
     * Handle the event.
     */
    public function handle(Failed $event)
    {
        $user_id = is_null($event->user) ? null : $event->user->id;

        $this->failedLoginAttemptService->createFailedLoginAttempt(
            $user_id, $event->credentials['email'], request()->ip()
        );

        Log::warning('Failed Login Attempt Details:');
        Log::warning('----------------------------------');
        Log::warning('Email: '.$event->credentials['email']);
        Log::warning('User ID: '.$event->user['id']);
        Log::warning('IP Address: '.request()->ip());
        Log::warning('----------------------------------');
        Log::warning('The login attempt was stored in the database successfully.');
        Log::warning('See additional details for reference.');
        Log::warning('');
    }
}
