<?php

namespace App\Providers;

use App\Classes\Mssql\Mssql;

use Illuminate\Support\ServiceProvider;

class MSSQLServiceProvider extends ServiceProvider
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
         $this->app->bind('MSSQL', function(){
            return new Mssql;
        });
    }
}
