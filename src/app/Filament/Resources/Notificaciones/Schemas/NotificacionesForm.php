<?php

namespace App\Filament\Resources\Notificaciones\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class NotificacionesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('notificaciones'),
                Select::make('donde')
                    ->options([
                        'inicio'=>'Inicio',
                        'calendario'=>'Calendario'
                    ]),
                Toggle::make('estado')
            ]);
    }
}
