<?php

namespace App\Providers;

use App\Modules\Authentication\Services\RecaptchaService;
use Illuminate\Support\ServiceProvider;

class RecaptchaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(RecaptchaService::class, function ($app) {
            return new RecaptchaService();
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
