<?php

namespace App\Filament\Resources\BeneficiarioPlans\Tables;

use App\Models\BeneficiarioTutor;
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
                    ->getStateUsing(function($record){
                        return $record->beneficiario->nombre . ' ' . $record->beneficiario->apellidos;
                    })
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nombreTutor')
                    ->getStateUsing(function($record){
                        return optional($record->beneficiario->tutorActivo?->tutor)->nombre
                            ? $record->beneficiario->tutorActivo->tutor->nombre . ' ' .
                            $record->beneficiario->tutorActivo->tutor->apellidos
                            : '-';
                    }),
                TextColumn::make('celularTutor')
                    ->getStateUsing(function ($record) {
                        return $record->beneficiario->tutorActivo?->tutor?->celular ?? '';
                    })
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->iconColor('success')
                    ->url(function ($record) {

                        $celular = $record->beneficiario->tutorActivo?->tutor?->celular;

                        if (!$celular) {
                            return null;
                        }

                        $celular = preg_replace('/\D/', '', $celular);

                        $mensaje = urlencode(
                            "Buenos días. Nos comunicamos respecto al beneficiario {$record->beneficiario->nombre}."
                        );

                        return "https://wa.me/591{$celular}";
                    })
                    ->openUrlInNewTab(),
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
