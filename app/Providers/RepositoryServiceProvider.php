<?php

namespace App\Providers;

use App\Repositories\TagRepository;
use App\Repositories\TaskRepository;
use App\Repositories\ClientRepository;
use App\Repositories\StatusRepository;
use App\Repositories\ProjectRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\TaskBoardRepository;
use App\Repositories\DepartmentRepository;
use App\Repositories\Contracts\TagRepositoryInterface;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Repositories\Contracts\ClientRepositoryInterface;
use App\Repositories\Contracts\StatusRepositoryInterface;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Repositories\Contracts\TaskBoardRepositoryInterface;
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
        $this->app->bind(
            TaskRepositoryInterface::class,
            TaskRepository::class
        );
        $this->app->bind(
            TagRepositoryInterface::class,
            TagRepository::class
        );
        $this->app->bind(
            StatusRepositoryInterface::class,
            StatusRepository::class
        );
        $this->app->bind(
            TaskBoardRepositoryInterface::class,
            TaskBoardRepository::class
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
