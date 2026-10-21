<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register custom admin user provider that only authenticates admin users
        Auth::provider('admins', function ($app, array $config) {
            return new \App\Auth\AdminUserProvider(
                $app['hash'],
                $config['model']
            );
        });

        // Configure Filament Admin Panel to use 'admin' guard
        if (class_exists(\Filament\Admin\AdminPanel::class)) {
            \Filament\Admin\AdminPanel::make()
                ->id('admin')
                ->path('admin')
                ->authGuard('admin')
                ->register();
        }

        // Ensure Spatie middleware aliases are available for routing
        $router = $this->app->make(\Illuminate\Routing\Router::class);
        $router->aliasMiddleware('role', \Spatie\Permission\Middleware\RoleMiddleware::class);
        $router->aliasMiddleware('permission', \Spatie\Permission\Middleware\PermissionMiddleware::class);
        $router->aliasMiddleware('role_or_permission', \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class);

        // Register the organizer Filament panel service provider if available
        if (class_exists(\App\Providers\OrganizerPanelServiceProvider::class)) {
            $this->app->register(\App\Providers\OrganizerPanelServiceProvider::class);
        }
    }
}

