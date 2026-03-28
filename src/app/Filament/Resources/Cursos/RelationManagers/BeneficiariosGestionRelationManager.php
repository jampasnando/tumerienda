<?php

namespace App\Filament\Resources\Cursos\RelationManagers;

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
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BeneficiariosGestionRelationManager extends RelationManager
{
    protected static string $relationship = 'beneficiariosGestion';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('beneficiario_id')
                    ->numeric(),
                TextInput::make('colegio_id')
                    ->numeric(),
                TextInput::make('gestion_id')
                    ->numeric(),
                TextInput::make('estado'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('beneficiario.nombre')
                ->label('Beneficiario')
                ->searchable(),

            TextColumn::make('gestion.anio')
                ->label('Gestión')
                ->colors([
                    'success' => fn ($record) => $record->gestion->activo,
                    'danger' => fn ($record) => !$record->gestion->activo,
                ]),

            TextColumn::make('estado')
                ->badge(),
        ])
        // ->headerActions([])
        // ->actions([])
        // ->bulkActions([])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make(),
                // AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                // DissociateAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->whereHas('gestion', fn ($q) =>
                        $q->where('activo', true)
                    )
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }

}
