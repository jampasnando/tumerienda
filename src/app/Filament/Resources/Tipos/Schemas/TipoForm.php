<?php

namespace App\Filament\Resources\Tipos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TipoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tipo')
                    ->required(),
            ]);
    }
}
