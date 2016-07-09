<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CheckStructureServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('CheckStructureService');
    }

}
