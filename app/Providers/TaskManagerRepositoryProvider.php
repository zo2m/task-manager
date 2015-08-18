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
    }
}