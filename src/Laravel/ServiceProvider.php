<?php

namespace Sahtepetrucci\ResponsiveCssSprites\Laravel;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Sahtepetrucci\ResponsiveCssSprites\SpritesHandler as SpritesHandler;

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