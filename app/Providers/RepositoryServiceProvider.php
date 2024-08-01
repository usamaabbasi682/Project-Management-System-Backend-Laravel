<?php

namespace App\Providers;

use App\Repositories\ClientRepository;
use App\Repositories\ProjectRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\DepartmentRepository;
use App\Repositories\Contracts\ClientRepositoryInterface;
use App\Repositories\Contracts\ProjectRepositoryInterface;
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
        $this->app->bind(
            ProjectRepositoryInterface::class,
            ProjectRepository::class
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
