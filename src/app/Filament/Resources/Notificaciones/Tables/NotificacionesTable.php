<?php

namespace App\Filament\Resources\Notificaciones\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class NotificacionesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('notificaciones'),
                TextColumn::make('donde'),
                ImageColumn::make('logo')
                    ->disk('public')
                    ->url(fn ($record) => $record->foto ? asset('storage/' . $record->foto) : null)
                    ->openUrlInNewTab(),
                ColorColumn::make('color'),
                ToggleColumn::make('estado')
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
