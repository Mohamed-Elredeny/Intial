<?php

namespace Modules\Intial\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Intial\Interfaces\UserRepositoryInterface;
use Modules\Intial\Repositories\UserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [

        ];
    }
}
