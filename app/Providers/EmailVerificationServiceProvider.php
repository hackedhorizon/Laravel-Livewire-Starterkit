<?php

namespace App\Providers;

use App\Modules\Registration\Services\EmailVerificationService;
use Illuminate\Support\ServiceProvider;

class EmailVerificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Only bind Email Verification service to the service container if it's enabled in the configuration file.
        if (config('services.should_verify_email')) {
            $this->app->bind(EmailVerificationService::class, function ($app) {
                return new EmailVerificationService();
            });
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
