<?php

namespace App\Filament\Resources\Artes\Pages;

use App\Filament\Resources\Artes\ArteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListArtes extends ListRecords
{
    protected static string $resource = ArteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
