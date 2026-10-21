<?php

namespace App\Providers;

use Filament\Admin\AdminPanel;
use Filament\Panel; // lightweight alias import
use Illuminate\Support\ServiceProvider;

class OrganizerPanelServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // nothing here
    }

    public function boot(): void
    {
        // Register a minimal organizer panel via Filament AdminPanel
        if (class_exists(\Filament\Admin\AdminPanel::class)) {
            AdminPanel::make()
                ->id('organizer')
                ->path('organizer-panel')
                ->register();
        }
    }
}
