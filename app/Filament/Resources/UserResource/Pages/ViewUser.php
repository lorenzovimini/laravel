<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\Concerns\HasPagePersistent;
use App\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    use HasPagePersistent;

    protected static string $resource = UserResource::class;

    /**
     * @throws \Exception
     */
    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
