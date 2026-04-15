<?php

namespace App\Filament\Resources\Beneficiarios\RelationManagers;

use App\Helpers\CodigoColegio;
use App\Models\Colegio;
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
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class ColegiosRelationManager extends RelationManager
{
    protected static string $relationship = 'colegios';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('colegio_id')
                    ->relationship('colegio', 'nombre')
                    ->preload()
                    ->searchable()
                    ->reactive()
                    ->required(),

                TextInput::make('codigo')
                    ->disabled()
                    ->dehydrated(true),

                Toggle::make('activo')
                    ->label('Activo')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('colegio_id')
            ->columns([
                TextColumn::make('colegio.nombre')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('activo')
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
                // TextColumn::make('tutor_id')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('codigo')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Asignar Colegio')
                    ->mutateDataUsing(function ($data) {

                        $colegio = Colegio::find($data['colegio_id']);

                        $data['codigo'] = CodigoColegio::generar(
                            $colegio->nombre,
                            $this->ownerRecord->id
                        );

                        return $data;
                    })
                    ->after(function ($record, $data) {

                    if ($data['activo']) {
                        \App\Models\BeneficiarioColegio::where('beneficiario_id', $this->ownerRecord->id)
                            ->where('id', '!=', $record->id)
                            ->update(['activo' => 0]);
                    }

                }),
                // AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make()
                    ->mutateDataUsing(function ($data, $record) {

                            $colegio = \App\Models\Colegio::find($data['colegio_id']);

                            $data['codigo'] = \App\Helpers\CodigoColegio::generar(
                                $colegio->nombre,
                                $record->beneficiario_id
                            );

                            return $data;
                        })
                    ->after(function ($record, $data) {

                        if ($data['activo']) {
                            \App\Models\BeneficiarioColegio::where('beneficiario_id', $this->ownerRecord->id)
                                ->where('id', '!=', $record->id)
                                ->update(['activo' => 0]);
                        }

                    }),
                // DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DissociateBulkAction::make(),
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
