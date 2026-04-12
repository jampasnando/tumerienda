<?php

namespace App\Filament\Resources\Suscripcions\Pages;

use App\Filament\Resources\Suscripcions\SuscripcionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSuscripcion extends ViewRecord
{
    protected static string $resource = SuscripcionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
