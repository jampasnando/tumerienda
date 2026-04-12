<?php

namespace App\Filament\Resources\Tutors\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class TutorsTable
{
    protected static function formatearNumero($numero)
    {
        // eliminar todo lo que no sea número
        $numero = preg_replace('/\D/', '', $numero);

        // si no empieza con 591, agregarlo
        if (!str_starts_with($numero, '591')) {
            $numero = '591' . $numero;
        }

        return $numero;
    }
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->searchable(),
                TextColumn::make('ci')
                    ->searchable(),
                TextColumn::make('telefono')
                    ->searchable(),
                TextColumn::make('celular')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->url(fn ($record) =>
                            $record->tutor?->celular
                                ? 'https://wa.me/' . self::formatearNumero($record->tutor->celular)
                                    . '?text=' . urlencode('Hola, me comunico por el servicio de meriendas')
                                : null
                        )
                    ->openUrlInNewTab()
                    ->color('success')
                    ->searchable(),
                TextColumn::make('genero')
                    ->searchable(),
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
            ])
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
