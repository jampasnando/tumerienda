<?php

namespace App\Filament\Resources\Beneficiarios\RelationManagers;

use App\Models\Beneficiario;
use App\Models\Entrega;
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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
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
    protected static ?string $title='Menus';
    public function getTableHeading(): string
    {
        return 'MENUS';
    }


    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
               Hidden::make('tutor_id')
                    ->default(fn () =>
                        $this->getOwnerRecord()
                            ->tutorActivo()
                            ->first()?->id
                    ),
               Select::make('menu_id')
                    ->relationship('menu', 'nombre')
                    ->required(),

               Select::make('gestion_id')
                    ->relationship('gestion', 'anio')
                    ->default(fn () => Gestion::where('activo', true)->first()?->id)
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
                TextColumn::make('menu.nombre')
                    ->label('Menu'),
                TextColumn::make('fecha_inicio')
                    ->date(),
                TextColumn::make('fecha_fin')
                    ->date(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Suscribir a menu'),
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
