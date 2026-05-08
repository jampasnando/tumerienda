<?php

namespace App\Filament\Resources\Plans\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre'),
                TextInput::make('precio')
                    ->numeric(),
                TextInput::make('nroentregas')
                    ->numeric(),
                Textarea::make('descripcion')
                    ->columnSpanFull(),
                Toggle::make('estado'),
                FileUpload::make('qr')
                    ->image()
                    ->disk('public')
                    ->directory('qr')
                    ->openable()
                    ->downloadable(),

            ]);
    }
}
