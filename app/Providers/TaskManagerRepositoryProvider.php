<?php

namespace TaskManager\Providers;

use Illuminate\Support\ServiceProvider;

class TaskManagerRepositoryProvider extends ServiceProvider
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
     *
     */
    public function register()
    {
        $this->app->bind
        (
            \TaskManager\Repositories\InterfaceClientRepository::class,
            \TaskManager\Repositories\Eloquent\ClientRepositoryEloquent::class
        );

        $this->app->bind
        (
            \TaskManager\Repositories\InterfaceProjectRepository::class,
            \TaskManager\Repositories\Eloquent\ProjectRepositoryEloquent::class
        );

        $this->app->bind
        (
            \TaskManager\Repositories\InterfaceProjectNoteRepository::class,
            \TaskManager\Repositories\Eloquent\ProjectNoteRepositoryEloquent::class
        );

        $this->app->bind
        (
            \TaskManager\Repositories\InterfaceUserRepository::class,
            \TaskManager\Repositories\Eloquent\UserRepositoryEloquent::class
        );

        $this->app->bind
        (
            \TaskManager\Repositories\InterfaceProjectTaskRepository::class,
            \TaskManager\Repositories\Eloquent\ProjectTaskRepositoryEloquent::class
        );

        $this->app->bind
        (
            \TaskManager\Repositories\InterfaceProjectMembersRepository::class,
            \TaskManager\Repositories\Eloquent\ProjectMembersRepositoryEloquent::class
        );

        $this->app->bind
        (
            \TaskManager\Repositories\InterfaceProjectFileRepository::class,
            \TaskManager\Repositories\Eloquent\ProjectFileRepositoryEloquent::class
        );
    }
}
