<?php

namespace App\Filament\Resources\Cobrosqrs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CobrosqrsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('alias')
                    ->searchable(),
                TextColumn::make('numeroOrdenOriginante')
                    ->searchable(),
                TextColumn::make('monto')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('idQr')
                    ->searchable(),
                TextColumn::make('moneda')
                    ->searchable(),
                TextColumn::make('fechaproceso')
                    ->searchable(),
                TextColumn::make('cuentaCliente')
                    ->searchable(),
                TextColumn::make('nombreCliente')
                    ->searchable(),
                TextColumn::make('documentoCliente')
                    ->searchable(),
                TextColumn::make('fechareg')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
