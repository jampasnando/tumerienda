<?php

namespace App\Filament\Resources\Beneficiarios\RelationManagers;

use Dom\Text;
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
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TutoresRelationManager extends RelationManager
{
    protected static string $relationship = 'tutores';

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

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tutor_id')
                    ->relationship('tutor', 'nombre')
                    ->searchable()
                    ->required(),

                Select::make('tipo')
                    ->options([
                        'padre' => 'Padre',
                        'madre' => 'Madre',
                        'apoderado' => 'Apoderado',
                    ])
                    ->required(),

                Toggle::make('activo')
                    ->default(true)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tutor_id')
            ->columns([
                TextColumn::make('tutor.nombre')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tutor.celular')
                    ->label('Celular')
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
                TextColumn::make('tipo')
                    ->searchable(),
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
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Asignar Tutor')
                    ->after(function ($record, $data) {

                        if ($data['activo'] === true) {

                            \App\Models\BeneficiarioTutor::where('beneficiario_id', $this->ownerRecord->id)
                                ->where('tipo', $data['tipo']) // opcional si usas por tipo
                                ->where('id', '!=', $record->id)
                                ->update(['activo' => false]);
                        }

                    }),
                // AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make()
                    ->after(function ($record, $data) {

                        if ($data['activo'] === true) {

                            \App\Models\BeneficiarioTutor::where('beneficiario_id', $this->ownerRecord->id)
                                ->where('tipo', $data['tipo']) // opcional si usas por tipo
                                ->where('id', '!=', $record->id)
                                ->update(['activo' => false]);
                        }

                    }),
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
