<?php

namespace App\Filament\Resources\Cobrosqrs\Pages;

use App\Filament\Resources\Cobrosqrs\CobrosqrResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCobrosqr extends ViewRecord
{
    protected static string $resource = CobrosqrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
