<?php

namespace App\Filament\Resources\Cursos\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CursoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('colegio_id')
                    ->label('Colegio')
                    ->relationship('colegio','nombre')
                    ->searchable()
                    ->required(),
                TextInput::make('nombre'),
                TextInput::make('nivel'),
                TextInput::make('estado'),
            ]);
    }
}
