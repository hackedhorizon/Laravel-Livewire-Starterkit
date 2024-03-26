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
        $this->app->bind(EmailVerificationService::class, function ($app) {
            return new EmailVerificationService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
