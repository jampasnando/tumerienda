<?php

namespace App\Filament\Resources\Cobrosqrs\Pages;

use App\Filament\Resources\Cobrosqrs\CobrosqrResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCobrosqr extends EditRecord
{
    protected static string $resource = CobrosqrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
