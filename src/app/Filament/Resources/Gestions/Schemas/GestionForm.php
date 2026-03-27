<?php

namespace App\Filament\Resources\Gestions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class GestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('anio')
                ->label('Año')
                ->default(fn () => now()->year),
                Toggle::make('activo')
                ->default(true),

            ]);
    }
}
