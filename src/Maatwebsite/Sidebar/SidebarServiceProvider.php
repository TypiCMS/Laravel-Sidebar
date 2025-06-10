<?php

namespace Maatwebsite\Sidebar;

use Illuminate\Support\ServiceProvider;

class SidebarServiceProvider extends ServiceProvider
{
    public function register()
    {
        $views = __DIR__ . '/../../resources/views';

        $this->loadViewsFrom($views, 'sidebar');

        $this->publishes([$views => base_path('resources/views/vendor/sidebar')], 'typicms-views');

        $this->app->singleton(SidebarManager::class);
    }
}
