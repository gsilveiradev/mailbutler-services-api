<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Repositories\Api\V1\UserRepository::class,
            \App\Repositories\Api\V1\UserRepositoryEloquent::class
        );
        $this->app->bind(
            \App\Repositories\Api\V1\UserTestRepository::class,
            \App\Repositories\Api\V1\UserTestRepositoryEloquent::class
        );
        //:end-bindings:
    }
}
