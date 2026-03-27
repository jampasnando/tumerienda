<?php

namespace App\Filament\Resources\Tutors\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TutorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre'),
                TextInput::make('ci'),
                Textarea::make('direccion')
                    ->columnSpanFull(),
                TextInput::make('telefono')
                    ->tel(),
                TextInput::make('celular'),
                Select::make('genero')
                    ->options([
                        'HOMBRE'=>'HOMBRE',
                        'MUJER'=>'MUJER'
                    ]),
                Textarea::make('comentarios')
                    ->columnSpanFull(),
            ]);
    }
}
