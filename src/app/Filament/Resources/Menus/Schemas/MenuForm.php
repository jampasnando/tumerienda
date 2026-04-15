<?php

namespace App\Filament\Resources\Menus\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
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
                TextInput::make('precio')
                    ->numeric(),
                FileUpload::make('foto')
                    ->disk('public')
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
