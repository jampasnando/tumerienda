<?php

namespace App\Filament\Resources\Artes\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ArteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('marcologin')
                    ->label('Marco de Login')
                    ->image()
                    ->directory('artes')
                    ->disk('public')
                    ->downloadable()
                    ->imageEditor()
                    ->previewable(),
                FileUpload::make('aviso1')
                    ->label('Aviso 1')
                    ->image()
                    ->directory('artes')
                    ->disk('public')
                    ->downloadable()
                    ->imageEditor()
                    ->previewable(),
                FileUpload::make('slides')
                    ->label('Slides')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->directory('artes')
                    ->disk('public')
                    ->downloadable()
                    ->imageEditor()
                    ->previewable(),
                FileUpload::make('pie')
                    ->label('Pie')
                    ->image()
                    ->directory('artes')
                    ->disk('public')
                    ->downloadable()
                    ->imageEditor()
                    ->previewable(),
            ]);
    }
}
