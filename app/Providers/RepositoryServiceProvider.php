<?php

namespace App\Providers;

use App\Repositories\ClientRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\DepartmentRepository;
use App\Repositories\Contracts\ClientRepositoryInterface;
use App\Repositories\Contracts\DepartmentRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            DepartmentRepositoryInterface::class,
            DepartmentRepository::class
        );
        $this->app->bind(
            ClientRepositoryInterface::class,
            ClientRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
