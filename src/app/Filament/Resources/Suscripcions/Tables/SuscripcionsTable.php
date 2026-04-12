<?php

namespace App\Filament\Resources\Suscripcions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SuscripcionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('beneficiario.nombre')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('oferta.nombre')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('menu.nombre')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('fecha')
                    ->date()
                    ->sortable(),
                TextColumn::make('beneficiario.colegioActivo.colegio.nombre')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('beneficiario.tutorActivo.tutor.nombre')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('estado')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
