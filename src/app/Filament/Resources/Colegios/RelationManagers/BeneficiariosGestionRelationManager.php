<?php

namespace App\Filament\Resources\Colegios\RelationManagers;

use App\Models\Gestion;
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

class BeneficiariosGestionRelationManager extends RelationManager
{
    protected static string $relationship = 'beneficiariosGestion';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('gestion_id')
                    ->relationship('gestion', 'anio',fn($query)=>$query->where('activo',true))
                    ->default(fn()=>Gestion::where('activo',true)->first()?->id)
                    ->required(),
                Select::make('beneficiario_id')
                    ->label('Beneficiario')
                    ->relationship('beneficiario', 'nombre',
                        fn ($query) => $query->whereDoesntHave('gestiones', function ($q) {
                            $q->where('curso_id', $this->getOwnerRecord()->id);
                        }))
                    ->searchable()
                    ->required(),
                Select::make('curso_id')
                    ->label('Curso')
                    ->relationship('curso','nombre',fn($query)=>$query->where('colegio_id',$this->getOwnerRecord()->id)),

                Select::make('estado')
                    ->options([
                        'activo' => 'Activo',
                        'retirado' => 'Retirado',
                    ])
                    ->default('activo'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('beneficiario.nombre')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('curso.nombre')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('gestion.anio')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('estado')
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
            ->headerActions([
                CreateAction::make(),
                // AssociateAction::make(),
            ])
            ->recordActions([
                // EditAction::make(),
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
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
