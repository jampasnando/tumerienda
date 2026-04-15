<?php

namespace App\Filament\Resources\Ingredientes\Schemas;

use App\Models\Ingrediente;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class IngredienteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre'),
                TextInput::make('unidad'),
                TextInput::make('costo_unitario')
                    ->numeric(),
                TextInput::make('categoria')
                    ->datalist(function () {
                        return Ingrediente::query()
                            ->whereNotNull('categoria')
                            ->distinct()
                            ->pluck('categoria')
                            ->toArray();
                    }),
            ]);
    }
}
