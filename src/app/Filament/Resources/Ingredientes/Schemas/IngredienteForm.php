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
                TextInput::make('categoria')
                    ->datalist(function () {
                        return Ingrediente::query()
                            ->whereNotNull('categoria')
                            ->distinct()
                            ->pluck('categoria')
                            ->toArray();
                    }),
                TextInput::make('nombre'),
                TextInput::make('unidad')
                    ->reactive(),
                TextInput::make('costo_unitario')
                    ->label(fn($get) => "Costo de 1 {$get('unidad')}")
                    ->numeric(),
                TextInput::make('unidad_receta')
                    ->label('Unidad en recetas')
                    ->reactive(),
                TextInput::make('equivalencia')
                    ->numeric()
                    ->label(fn ($get) => "Un {$get('unidad')} equivale a ??? {$get('unidad_receta')}"),

            ])
            ->columns(3);
    }
}
