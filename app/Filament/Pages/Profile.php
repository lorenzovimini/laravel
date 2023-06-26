<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class Profile extends \RyanChandler\FilamentProfile\Pages\Profile
{
    use HasPageShield;
    protected static int $priority = 1;
    /*
    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }
    */

    protected static function getNavigationGroup(): string|null
    {
        return 'Settings';
    }

    /*
    public function mount(): void
    {
        abort_unless(auth()->user()->hasRole('super_admin'), 403);
        parent::mount();
    }
    */
}
