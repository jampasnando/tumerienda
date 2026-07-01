<?php

namespace App\Filament\Resources\BeneficiarioPlans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class BeneficiarioPlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('plan.nombre')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('plan.precio')
                    ->label('Precio')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('beneficiario.nombre')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('estado')
                    ->boolean(),
                TextColumn::make('nrorecibidos')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Pagado')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
