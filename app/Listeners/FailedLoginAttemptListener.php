<?php

namespace App\Listeners;

use App\Modules\Authentication\Services\WriteFailedLoginAttemptService;
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

        // Set the user_id. If user doesn't exists yet, it will get a default null value.
        $user_id = is_null($event->user) ? null : $event->user->id;

        // If the user doesn't exists, we don't create a log.
        if (! $user_id) {
            return;
        }

        // If user exists, we log the failed login attempt.
        $this->failedLoginAttemptService->createFailedLoginAttempt(
            $user_id,
            $event->user['email'],
            request()->ip()
        );

        Log::warning('Failed Login Attempt Details:');
        Log::warning('----------------------------------');
        Log::warning('Email: '.$event->user['email']);
        Log::warning('User ID: '.$event->user['id']);
        Log::warning('IP Address: '.request()->ip());
        Log::warning('----------------------------------');
        Log::warning('The login attempt was stored in the database successfully.');
        Log::warning('See additional details for reference.');
        Log::warning('');
    }
}
