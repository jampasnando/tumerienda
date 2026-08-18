<?php

namespace App\Filament\Resources\Cobrosqrs\Pages;

use App\Filament\Resources\Cobrosqrs\CobrosqrResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCobrosqrs extends ListRecords
{
    protected static string $resource = CobrosqrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
