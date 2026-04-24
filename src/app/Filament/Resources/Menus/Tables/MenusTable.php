<?php

namespace App\Filament\Resources\Menus\Tables;

use DB;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
class MenusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tipo')
                    ->sortable(),
                TextColumn::make('nombre')
                    ->sortable()
                    ->searchable(),
                // TextColumn::make('costo')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('costo_total')
                    ->label('Costo')
                    ->numeric()
                    ->sortable(),

                // TextColumn::make('precio')
                //     ->numeric()
                //     ->sortable(),
                IconColumn::make('activo')
                    ->sortable()
                    ->boolean(),
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
                ImageColumn::make('foto')
                    ->disk('public')
                    ->url(fn ($record) => $record->foto ? asset('storage/' . $record->foto) : null)
                    ->openUrlInNewTab(),

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
                    BulkAction::make('sumarCostos')
                        ->label('+ Sumar costos seleccionados')
                        ->action(function (Collection $records) {

                            $ids = $records->pluck('id');

                            $total = DB::table('menus')
                                ->whereIn('menus.id', $ids)
                                ->join('ingrediente_menu', 'menus.id', '=', 'ingrediente_menu.menu_id')
                                ->join('ingredientes', 'ingredientes.id', '=', 'ingrediente_menu.ingrediente_id')
                                ->selectRaw('SUM((ingrediente_menu.cantidad / ingredientes.equivalencia) * ingredientes.costo_unitario) as total')
                                ->value('total') ?? 0;

                            \Filament\Notifications\Notification::make()
                                ->title('Total seleccionado')
                                ->body(
                                        count($records) . ' menús → Bs ' . number_format($total, 2)
                                    )
                                ->success()
                                ->send();
                        }),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
