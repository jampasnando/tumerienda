<?php

namespace App\Filament\Resources\Ofertas\RelationManagers;

use App\Models\Grupo;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MenuOfertasRelationManager extends RelationManager
{
    protected static string $relationship = 'menuOfertas';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('menu_id')
                    ->required()
                    ->relationship('menu','nombre')
                    ->preload()
                    ->searchable(),
                Select::make('grupo_id')
                    ->required()
                    ->options(Grupo::pluck('nombre','id')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('menu.nombre')
            ->columns([
                TextColumn::make('grupo.nombre'),
                TextColumn::make('menu.tipo')
                    ->label('Tipo'),
                TextColumn::make('menu.nombre')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                // AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                // DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
