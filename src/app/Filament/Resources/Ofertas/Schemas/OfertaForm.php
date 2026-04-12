<?php

namespace App\Filament\Resources\Ofertas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class OfertaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre'),
                DatePicker::make('fecha_inicio'),
                DatePicker::make('fecha_fin'),
                Toggle::make('activo')
                    ->default(true),
            ]);
    }
}
