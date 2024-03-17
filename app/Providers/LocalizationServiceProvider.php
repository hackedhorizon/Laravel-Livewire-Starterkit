<?php

namespace App\Providers;

use App\Modules\Localization\Services\LocalizationService;
use App\Modules\UserManagement\Repositories\ReadUserRepository;
use App\Modules\UserManagement\Repositories\WriteUserRepository;
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
            $this->app->bind(LocalizationService::class, function ($app) {
                return new LocalizationService(
                    $app->make(ReadUserRepository::class),
                    $app->make(WriteUserRepository::class)
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
