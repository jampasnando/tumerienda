<?php

namespace App\Filament\Resources\Packs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PackForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre'),
                TextInput::make('precio')
                    ->numeric(),
                Textarea::make('descripcion'),
                Select::make('estado')
                    ->options([
                        'cerrado' => 'Cerrado',
                        'abierto' => 'Abierto'
                    ])
                    ->default('cerrado'),
            ]);
    }
}
