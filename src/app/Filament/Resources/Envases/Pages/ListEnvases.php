<?php

namespace App\Filament\Resources\Envases\Pages;

use App\Filament\Resources\Envases\EnvaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEnvases extends ListRecords
{
    protected static string $resource = EnvaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
