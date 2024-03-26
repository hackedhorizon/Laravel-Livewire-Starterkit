<?php

namespace App\Providers;

use App\Modules\Localization\Services\LocalizationService;
use App\Modules\UserManagement\Services\ReadUserService;
use App\Modules\UserManagement\Services\WriteUserService;
use Illuminate\Support\ServiceProvider;

class LocalizationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(LocalizationService::class, function ($app) {
            return new LocalizationService(
                $app->make(WriteUserService::class),
                $app->make(ReadUserService::class)
            );
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
