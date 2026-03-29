<?php

namespace App\Filament\Resources\Cursos\RelationManagers;

use App\Models\BeneficiarioTutor;
use App\Models\Gestion;
use App\Models\Menu;
use App\Models\Subscripcion;
use Filament\Actions\Action;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkAction;
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
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class BeneficiariosGestionRelationManager extends RelationManager
{
    protected static string $relationship = 'beneficiariosGestion';
    public function getTableHeading(): string|Htmlable|null
    {
        return 'Beneficiarios Gestión: '.Gestion::where('activo',true)->first()?->anio;
    }
    protected function crearSubscripcion($record, array $data, $tutores)
    {
        $beneficiarioId = $record->beneficiario_id;

        $tutor = $tutores[$beneficiarioId] ?? null;

        if (!$tutor) return;

        $existe = Subscripcion::where('beneficiario_id', $beneficiarioId)
            ->where('menu_id', $data['menu_id'])
            ->where('estado', 'activo')
            ->where(function ($q) use ($data) {
                $q->whereBetween('fecha_inicio', [$data['fecha_inicio'], $data['fecha_fin']])
                ->orWhereBetween('fecha_fin', [$data['fecha_inicio'], $data['fecha_fin']]);
            })
            ->exists();

        if ($existe) return;

        Subscripcion::create([
            'beneficiario_id' => $beneficiarioId,
            'tutor_id' => $tutor->tutor_id,
            'menu_id' => $data['menu_id'],
            'gestion_id' => $record->gestion_id,
            'colegio_id' => $record->colegio_id,
            'curso_id' => $record->curso_id,
            'fecha_inicio' => $data['fecha_inicio'],
            'fecha_fin' => $data['fecha_fin'],
            'estado' => $data['estado'],
        ]);
    }
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('beneficiario_id')
                    ->label('Beneficiario')
                    ->relationship('beneficiario', 'nombre',
                        fn ($query) => $query->whereDoesntHave('gestiones', function ($q) {
                            $q->where('curso_id', $this->getOwnerRecord()->id);
                        }))
                    ->searchable()
                    ->required(),

                Select::make('gestion_id')
                    ->relationship('gestion', 'anio',fn($query)=>$query->where('activo',true))
                    ->default(fn()=>Gestion::where('activo',true)->first()?->id)
                    ->required(),

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
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'activo' => 'success',
                    'inactivo' => 'danger',
                })
        ])
        // ->headerActions([])
        // ->actions([])
        // ->bulkActions([])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make()
                ->label('Inscribir Beneficiario'),
                // AssociateAction::make(),
            ])
            ->recordActions([
                Action::make('asignarMenu')
                    ->label('Asignar menú')
                    ->icon('heroicon-o-cake')
                    ->color('success')
                    ->schema([
                        Select::make('menu_id')
                            ->options(Menu::pluck('nombre', 'id'))
                            ->required(),

                        DatePicker::make('fecha_inicio')->required(),
                        DatePicker::make('fecha_fin')->required(),

                        Select::make('estado')
                            ->options([
                                'activo' => 'Activo',
                                'cancelado' => 'Cancelado',
                            ])
                            ->default('activo')
                            ->required(),
                    ])

                    ->action(function ($record, array $data) {

                        DB::transaction(function () use ($record, $data) {

                            $tutores = BeneficiarioTutor::where('beneficiario_id', $record->beneficiario_id)
                                ->where('estado', 'activo')
                                ->get()
                                ->keyBy('beneficiario_id');

                            $this->crearSubscripcion($record, $data, $tutores);
                        });
                    }),
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
                    BulkAction::make('asignarMenu')
                        ->label('Asignar menú')
                        ->icon('heroicon-o-cake')
                        ->color('success')

                        ->schema([

                            Select::make('menu_id')
                                ->label('Menú')
                                ->options(Menu::pluck('nombre', 'id'))
                                ->searchable()
                                ->required(),

                            DatePicker::make('fecha_inicio')
                                ->default(now())
                                ->required(),

                            DatePicker::make('fecha_fin')
                                ->required()
                                ->after('fecha_inicio'),

                            Select::make('estado')
                                ->options([
                                    'activo' => 'Activo',
                                    'cancelado' => 'Cancelado',
                                ])
                                ->default('activo')
                                ->required(),

                        ])

                        ->action(function ($records, array $data) {

                            DB::transaction(function () use ($records, $data) {

                                // 🔥 1. Cargar todos los tutores en una sola query
                                $tutores = BeneficiarioTutor::whereIn(
                                        'beneficiario_id',
                                        $records->pluck('beneficiario_id')
                                    )
                                    ->where('estado', 'activo')
                                    ->get()
                                    ->keyBy('beneficiario_id');
                                // dd($tutores);
                                foreach ($records as $record) {

                                    // ✅ 2. Definir primero la variable
                                    $beneficiarioId = $record->beneficiario_id;

                                    // ✅ 3. Usar el array ya cargado
                                    $tutor = $tutores[$beneficiarioId] ?? null;

                                    if (!$tutor) {
                                        continue; // o lanzar excepción
                                    }

                                    // 🔥 Evitar duplicados
                                    $existe = Subscripcion::where('beneficiario_id', $beneficiarioId)
                                        ->where('menu_id', $data['menu_id'])
                                        ->where('estado', 'activo')
                                        ->where(function ($q) use ($data) {
                                            $q->whereBetween('fecha_inicio', [$data['fecha_inicio'], $data['fecha_fin']])
                                            ->orWhereBetween('fecha_fin', [$data['fecha_inicio'], $data['fecha_fin']]);
                                        })
                                        ->exists();

                                    if ($existe) continue;

                                    // 🚀 Crear subscripción
                                    Subscripcion::create([
                                        'beneficiario_id' => $beneficiarioId,
                                        'tutor_id' => $tutor->tutor_id,
                                        'menu_id' => $data['menu_id'],
                                        'gestion_id' => $record->gestion_id,
                                        'colegio_id' => $record->colegio_id,
                                        'curso_id' => $record->curso_id,
                                        'fecha_inicio' => $data['fecha_inicio'],
                                        'fecha_fin' => $data['fecha_fin'],
                                        'estado' => $data['estado'],
                                    ]);
                                }
                            });
                        }),
                        // Notification::make()
                        //     ->title('Menú asignado correctamente')
                        //     ->success()
                        //     ->send()
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
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $curso = $this->getOwnerRecord();

        $data['curso_id'] = $curso->id;
        $data['colegio_id'] = $curso->colegio_id;

        return $data;
    }
}
