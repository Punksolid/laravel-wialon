<?php

namespace Punksolid\Wialon;

use Illuminate\Support\ServiceProvider;

class WialonServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/wialon.php' => config_path('wialon.php'),
        ], 'wialon');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
