<?php

namespace Sahtepetrucci\SpritesGenerator\Laravel;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Sahtepetrucci\SpritesGenerator\SpritesHandler as SpritesHandler;

/**
 * Class SpritesServiceProvider
 */
class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('SpritesHandler', SpritesHandler::class);
    }
}