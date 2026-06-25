<?php

namespace App\Filament\Resources\Envases\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EnvaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre'),
                TextInput::make('costo')
                    ->numeric(),
                Textarea::make('descripcion')
                    ->columnSpanFull(),
                Toggle::make('activo'),
            ]);
    }
}
