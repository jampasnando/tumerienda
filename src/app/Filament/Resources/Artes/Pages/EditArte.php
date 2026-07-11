<?php

namespace App\Filament\Resources\Artes\Pages;

use App\Filament\Resources\Artes\ArteResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditArte extends EditRecord
{
    protected static string $resource = ArteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
