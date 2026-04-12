<?php

namespace App\Filament\Resources\Suscripcions\Pages;

use App\Filament\Resources\Suscripcions\SuscripcionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditSuscripcion extends EditRecord
{
    protected static string $resource = SuscripcionResource::class;
    public static function canEdit(Model $record): bool
    {
        return false;
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
