<?php

namespace App\Filament\Resources\Artes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ArtesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('marcologin')
                    ->label('Marco de Login')
                    ->disk('public')
                    ->openUrlInNewTab(
                        fn ($record) => $record->marcologin ? asset('storage/' . $record->marcologin) : null
                    )
                    ->searchable(),
                ImageColumn::make('aviso1')
                    ->label('Aviso 1')
                    ->disk('public')
                    ->openUrlInNewTab(
                        fn ($record) => $record->marcologin ? asset('storage/' . $record->marcologin) : null
                    )
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
