<?php

namespace App\Filament\Resources\Menus\Schemas;

use Filament\Forms\Components\DatePicker;
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
                TextInput::make('nombre'),
                RichEditor::make('descripcion')
                    ->columnSpanFull(),
                TextInput::make('costo')
                    ->numeric(),
                TextInput::make('precio'),
                DatePicker::make('fechainicio')
                    ->label('Fecha Incio'),
                DatePicker::make('fechafin')
                    ->label('Fecha Fin'),
                Toggle::make('activo'),
                FileUpload::make('foto')
                    ->disk('public')
                    ->directory('menu')
                    ->downloadable()
                    ->imageEditor()
                    ->image()
                    ->previewable(),
            ]);
    }
}
