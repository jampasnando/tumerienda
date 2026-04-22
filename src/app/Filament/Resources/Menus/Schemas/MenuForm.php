<?php

namespace App\Filament\Resources\Menus\Schemas;

use App\Models\Tipo;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required(),
                Toggle::make('activo')
                    ->default(true),
                Textarea::make('descripcion')
                    ->columnSpanFull(),
                // TextInput::make('precio')
                //     ->numeric(),
                Select::make('tipo')
                    ->options(function () {
                        return Tipo::query()
                            ->pluck('tipo', 'tipo');
                    })
                    ->required(),
                FileUpload::make('foto')
                    ->disk('public')
                    ->directory('menu')
                    ->image()
                    ->downloadable()
                    ->imageEditor()
                    ->previewable(),
                RichEditor::make('preparacion')
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
