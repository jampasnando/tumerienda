<?php

namespace App\Filament\Resources\Beneficiarios\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Laravel\Pail\Options;

class BeneficiarioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre'),
                DatePicker::make('fechanac'),
                Select::make('genero')
                    ->options([
                        'masculino'=>'Masculino',
                        'femenino'=>'Femenino'
                    ]),
                TextInput::make('codigo'),
                Textarea::make('comentarios')
                    ->columnSpanFull(),
            ]);
    }
}
