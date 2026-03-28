<?php

namespace App\Filament\Resources\Colegios\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ColegioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre'),
                TextInput::make('telefono')
                    ->tel(),
                TextInput::make('celular'),
                TextInput::make('contacto'),
                Textarea::make('direccion'),
                TextInput::make('latitud'),
                TextInput::make('longitud'),
            ]);
    }
}
