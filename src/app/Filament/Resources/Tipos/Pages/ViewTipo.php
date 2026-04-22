<?php

namespace App\Filament\Resources\Tipos\Pages;

use App\Filament\Resources\Tipos\TipoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTipo extends ViewRecord
{
    protected static string $resource = TipoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
