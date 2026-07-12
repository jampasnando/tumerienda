<?php

namespace App\Filament\Resources\Notificaciones\Pages;

use App\Filament\Resources\Notificaciones\NotificacionesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNotificaciones extends EditRecord
{
    protected static string $resource = NotificacionesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
