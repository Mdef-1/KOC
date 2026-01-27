<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register components
        $this->loadViewComponentsAs('layouts', [
            'app' => \App\View\Components\Layouts\App::class,
            'guest' => \App\View\Components\Layouts\Guest::class,
        ]);
    }
}
