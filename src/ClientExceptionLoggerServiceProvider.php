<?php

namespace ExceptionLogger;

use Illuminate\Support\ServiceProvider;

class ClientExceptionLoggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        require __DIR__.'/routes/routes.php';
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('deferdie-ExceptionLogger', function(){
            return new ClientExceptionLogger();
        });
    }
}
