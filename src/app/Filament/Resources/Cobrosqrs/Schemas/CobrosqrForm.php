<?php

namespace App\Filament\Resources\Cobrosqrs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CobrosqrForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('alias')
                    ->required(),
                TextInput::make('numeroOrdenOriginante')
                    ->required(),
                TextInput::make('monto')
                    ->required()
                    ->numeric(),
                TextInput::make('idQr')
                    ->required(),
                TextInput::make('moneda')
                    ->required(),
                TextInput::make('fechaproceso')
                    ->required(),
                TextInput::make('cuentaCliente')
                    ->required(),
                TextInput::make('nombreCliente')
                    ->required(),
                TextInput::make('documentoCliente')
                    ->required(),
                DateTimePicker::make('fechareg')
                    ->required(),
            ]);
    }
}
