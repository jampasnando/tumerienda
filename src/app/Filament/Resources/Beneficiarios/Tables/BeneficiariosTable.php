<?php

namespace App\Filament\Resources\Beneficiarios\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use PhpParser\Node\Stmt\Label;

class BeneficiariosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombrecolegioActivo.codigo')
                    ->label('Código'),
                TextColumn::make('nombre')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('apellidos')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nombrecolegioActivo.colegio.nombre'),
                TextColumn::make('fechanac')
                    ->date()
                    ->sortable(),
                TextColumn::make('genero')
                    ->searchable(),
                TextColumn::make('nombrecolegioActivo.colegio.nombre')
                    ->label('ColegioActual')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('codigo')
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
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
