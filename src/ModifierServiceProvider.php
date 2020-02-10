<?php

namespace Creatortsv\EloquentPipelinesModifier;

use Illuminate\Support\ServiceProvider;

class ModifierServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->publishes([
            __DIR__ . '/../config/modifier.php' => config_path('modifier.php'),
        ]);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
