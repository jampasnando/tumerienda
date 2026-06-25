<?php

namespace App\Filament\Resources\Envases\Pages;

use App\Filament\Resources\Envases\EnvaseResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditEnvase extends EditRecord
{
    protected static string $resource = EnvaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
