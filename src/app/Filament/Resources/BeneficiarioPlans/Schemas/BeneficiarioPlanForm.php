<?php

namespace App\Filament\Resources\BeneficiarioPlans\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use PhpParser\Node\Expr\Cast\Bool_;

class BeneficiarioPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('plan_id')
                    ->options(function () {
                        return \App\Models\Plan::pluck('nombre', 'id');
                    }),
                Select::make('beneficiario_id')
                    ->options(function () {
                        return \App\Models\Beneficiario::pluck('nombre', 'id');
                    }),
                Toggle::make('estado')
                    ->default(true),
                TextInput::make('nrorecibidos')
                    ->numeric()
                    ->default(0),
                Textarea::make('detalle')
                // TextInput::make('pagado')
                //     ->numeric(),
            ]);
    }
}
