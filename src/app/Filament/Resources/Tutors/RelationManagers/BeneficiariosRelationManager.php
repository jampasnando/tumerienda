<?php

namespace App\Filament\Resources\Tutors\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BeneficiariosRelationManager extends RelationManager
{
    protected static string $relationship = 'beneficiarios';
    protected array $pivotData = [];
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre'),
                DatePicker::make('fechanac'),
                TextInput::make('genero'),
                TextInput::make('codigo'),
                Textarea::make('comentarios')
                    ->columnSpanFull(),
                // 🔥 CAMPOS DEL PIVOT
                Select::make('pivot.tipo')
                    ->label('Tipo')
                    ->options([
                        'padre' => 'Padre',
                        'madre' => 'Madre',
                        'tutor' => 'Tutor',
                    ])
                    ->required(),

                Select::make('pivot.estado')
                    ->label('Estado')
                    ->options([
                        'activo' => 'Activo',
                        'inactivo' => 'Inactivo',
                    ])
                    ->default('activo')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
            ->columns([
                TextColumn::make('nombre')
                    ->searchable(),
                TextColumn::make('fechanac')
                    ->date()
                    ->sortable(),
                TextColumn::make('genero')
                    ->searchable(),
                TextColumn::make('codigo')
                    ->searchable(),
                TextColumn::make('pivot.tipo')
                    ->label('Tipo'),

                TextColumn::make('pivot.estado')
                    ->label('EstadoTutor')
                    ->badge()
                    ->color(fn ($state) => $state === 'activo' ? 'success' : 'danger'),
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
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make()
                ->mutateDataUsing(function (array $data): array {
                    // separar datos pivot
                    $this->pivotData = $data['pivot'] ?? [];
                    unset($data['pivot']);

                    return $data;
                })
                ->after(function ($record) {

                    $tutor = $this->getOwnerRecord();

                    // 🔥 guardar pivot manualmente
                    $tutor->beneficiarios()->syncWithoutDetaching([
                        $record->id => $this->pivotData
                    ]);
                }),
                AttachAction::make(),
            ])
            ->recordActions([
                EditAction::make()
                ->fillForm(function ($record) {

                    $pivot = $record->pivot;

                    return [
                        'nombre' => $record->nombre,
                        'fechanac' => $record->fechanac,
                        'genero' => $record->genero,
                        'codigo' => $record->codigo,
                        'comentarios' => $record->comentarios,

                        // 🔥 cargar pivot
                        'pivot' => [
                            'tipo' => $pivot->tipo ?? null,
                            'estado' => $pivot->estado ?? null,
                        ],
                    ];
                })
                ->mutateDataUsing(function (array $data): array {
                    $this->pivotData = $data['pivot'] ?? [];
                    unset($data['pivot']);

                    return $data;
                })
                ->after(function ($record) {

                    $tutor = $this->getOwnerRecord();

                    $tutor->beneficiarios()->syncWithoutDetaching([
                        $record->id => $this->pivotData
                    ]);
                }),
                DetachAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
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
