<?php

namespace App\Filament\Resources\Ofertas\Schemas;

use App\Models\Pack;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class OfertaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('nombre'),

                Select::make('pack_id')
                    ->relationship('pack','nombre')
                    ->searchable()
                    ->preload(),
                DatePicker::make('fecha'),
                // DatePicker::make('fecha_inicio'),
                // DatePicker::make('fecha_fin'),
                // Toggle::make('activo')
                //     ->default(true),
            ]);
    }
}
