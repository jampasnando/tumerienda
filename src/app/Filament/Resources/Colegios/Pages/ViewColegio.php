<?php

namespace App\Filament\Resources\Colegios\Pages;

use App\Filament\Resources\Colegios\ColegioResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewColegio extends ViewRecord
{
    protected static string $resource = ColegioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
