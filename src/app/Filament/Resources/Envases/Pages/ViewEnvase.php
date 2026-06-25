<?php

namespace App\Filament\Resources\Envases\Pages;

use App\Filament\Resources\Envases\EnvaseResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEnvase extends ViewRecord
{
    protected static string $resource = EnvaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
