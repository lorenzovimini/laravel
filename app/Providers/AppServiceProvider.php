<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Illuminate\Support\ServiceProvider;

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
        /*
        Filament::registerNavigationGroups([
            'Shop',
            'Anagrafiche',
            'Impostazioni',
            'Settings',
            'System',
        ]);
        */
        Filament::serving(static function () {
            Filament::registerViteTheme(['resources/css/filament.css', 'resources/css/app.css']);
        });

        Filament::serving(static function () {
            Filament::registerUserMenuItems([
                UserMenuItem::make()
                    ->label('Profilo')
                    ->url(route('filament.pages.profile'))
                    ->icon('heroicon-o-user'),
                // ...
            ]);
        });
    }
}
