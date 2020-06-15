<?php

namespace Sprites;

use Illuminate\Support\ServiceProvider;

/**
 * Class SpritesServiceProvider
 */
class SpritesServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Sprites', SpritesHandler::class);
    }
}