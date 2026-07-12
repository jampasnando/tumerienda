<?php

namespace App\Filament\Resources\Notificaciones\Pages;

use App\Filament\Resources\Notificaciones\NotificacionesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNotificaciones extends ListRecords
{
    protected static string $resource = NotificacionesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
