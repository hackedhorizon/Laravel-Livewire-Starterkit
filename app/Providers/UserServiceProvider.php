<?php

namespace App\Providers;

use App\Modules\User\Interfaces\ReadUserServiceInterface;
use App\Modules\User\Interfaces\WriteUserServiceInterface;
use App\Modules\User\Services\ReadUserService;
use App\Modules\User\Services\WriteUserService;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(WriteUserServiceInterface::class, WriteUserService::class);
        $this->app->bind(ReadUserServiceInterface::class, ReadUserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
