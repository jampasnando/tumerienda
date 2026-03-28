<?php

namespace App\Filament\Resources\Beneficiarios\RelationManagers;

use App\Models\Entrega;
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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubscripcionesRelationManager extends RelationManager
{
    protected static string $relationship = 'subscripciones';
    protected static ?string $title='Suscripciones';
    public function getTableHeading(): string
    {
        return 'SUSCRIPCIONES';
    }


    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
               Select::make('tutor_id')
                    ->relationship('tutor', 'nombre')
                    ->required(),

               Select::make('menu_id')
                    ->relationship('menu', 'nombre')
                    ->required(),

               Select::make('gestion_id')
                    ->relationship('gestion', 'anio')
                    ->default(fn () => \App\Models\Gestion::where('activo', true)->first()?->id)
                    ->required(),

               DatePicker::make('fecha_inicio')
                    ->required(),

               DatePicker::make('fecha_fin')
                    ->required()
                    ->after('fecha_inicio'),

               Select::make('estado')
                    ->options([
                        'activo' => 'Activo',
                        'cancelado' => 'Cancelado',
                    ])
                    ->default('activo'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('id')
                    ->searchable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make(),
                    // ->after(function ($record) {
                    //     $inicio = \Carbon\Carbon::parse($record->fecha_inicio);
                    //     $fin = \Carbon\Carbon::parse($record->fecha_fin);

                    //     for ($fecha = $inicio->copy(); $fecha->lte($fin); $fecha->addDay()) {
                    //         if ($fecha->isWeekend()) continue;
                    //         Entrega::create([
                    //             'subscripcion_id' => $record->id,
                    //             'fecha' => $fecha,
                    //             'estado' => 'pendiente',
                    //         ]);
                    //     }
                    // }),
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
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['beneficiario_id'] = $this->getOwnerRecord()->id;
        return $data;
    }
}
