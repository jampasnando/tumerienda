<?php

namespace App\Filament\Resources\BeneficiarioPlans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BeneficiarioPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('plan_id')
                    ->numeric(),
                TextInput::make('beneficiario_id')
                    ->numeric(),
                TextInput::make('estado'),
                TextInput::make('nrorecibidos')
                    ->numeric(),
                TextInput::make('pagado')
                    ->numeric(),
            ]);
    }
}
