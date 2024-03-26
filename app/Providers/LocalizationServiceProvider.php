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
        // Only bind localization service to the service container if it's enabled in the configuration file.
        if (config('services.should_have_localization')) {
            $this->app->singleton(LocalizationService::class, function ($app) {
                return new LocalizationService(
                    $app->make(WriteUserService::class),
                    $app->make(ReadUserService::class)
                );
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
