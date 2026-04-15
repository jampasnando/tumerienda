<?php

namespace App\Filament\Resources\Menus\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IngredientesMenuRelationManagerRelationManager extends RelationManager
{
    protected static string $relationship = 'ingredientesMenu';
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $ingrediente = \App\Models\Ingrediente::find($data['ingrediente_id']);

        $data['costo'] = ($data['cantidad'] ?? 0) * ($ingrediente->costo_unitario ?? 0);

        return $data;
    }
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('ingrediente_id')
                ->label('Ingrediente')
                ->options(
                    \App\Models\Ingrediente::whereNotNull('nombre')
                        ->get()
                        ->groupBy('categoria')
                        ->map(function ($items) {
                            return $items->mapWithKeys(function ($item) {
                                return [
                                    $item->id => $item->nombre
                                        . ' (' . $item->unidad
                                        . ' - Bs ' . number_format($item->costo_unitario, 3) . ')'
                                ];
                            });
                        })
                        ->toArray()
                )
                ->searchable()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {

                    $ing = \App\Models\Ingrediente::find($state);

                    if ($ing) {
                        $set('costo_unitario', $ing->costo_unitario);
                        $set('unidad', $ing->unidad);
                    }

                })
                ->required(),

                TextInput::make('cantidad')
                    ->numeric()
                    ->required()
                    ->default(1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('ingrediente.nombre')
                    ->label('Ingrediente'),

                TextColumn::make('cantidad'),

                TextColumn::make('ingrediente.unidad')
                    ->label('Unidad'),

                TextColumn::make('costo_calculado')
                    ->label('Costo')
                    ->getStateUsing(function ($record) {

                        if (!$record->ingrediente) {
                            return '—';
                        }

                        return 'Bs ' . number_format(
                            $record->cantidad * $record->ingrediente->costo_unitario,
                            2
                        );
                    }),

            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
