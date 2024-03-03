<?php

namespace App\Providers;

use App\Modules\Auth\Services\RecaptchaService;
use Illuminate\Support\ServiceProvider;

class RecaptchaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Only bind Google's recaptcha service to the service container if it's enabled in the configuration file.
        if (config('services.should_have_recaptcha')) {
            $this->app->bind(RecaptchaService::class, function ($app) {
                return new RecaptchaService();
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
