<?php

namespace App\Filament\Pages;

use App\Models\Colegio;
use App\Models\Curso;
use App\Models\Entrega;
use App\Models\Subscripcion;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EntregasHoy extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationLabel = 'Entregas de hoy';
    // protected static $navigationIcon = 'heroicon-o-calendar-days';
    // protected static $navigationGroup = 'Operación';
    protected static ?int $navigationSort = 1;

    protected  string $view = 'filament.pages.entregas-hoy';

    protected array $entregasHoy = [];

    public function mount(): void
    {
        $hoy = today();

        $this->entregasHoy = Entrega::whereDate('fecha', $hoy)
            ->get()
            ->keyBy('subscripcion_id')
            ->toArray();
    }

    public function table(Table $table): Table
    {
        $hoy = today();

        return $table
            ->query(
                Subscripcion::query()
                    ->where('estado', 'activo')
                    ->whereDate('fecha_inicio', '<=', $hoy)
                    ->whereDate('fecha_fin', '>=', $hoy)
                    ->with([
                        'beneficiario',
                        'menu',
                        'beneficiario.gestiones.curso.colegio',
                    ])
            )

            ->columns([

                TextColumn::make('beneficiario.nombre')
                    ->label('Beneficiario')
                    ->searchable(),

                TextColumn::make('beneficiario.gestiones.0.curso.colegio.nombre')
                    ->label('Colegio'),

                TextColumn::make('beneficiario.gestiones.0.curso.nombre')
                    ->label('Curso'),

                TextColumn::make('menu.nombre')
                    ->label('Menú'),

                TextColumn::make('estado_dia')
                    ->label('Estado')
                    ->getStateUsing(fn ($record) =>
                        $this->entregasHoy[$record->id]['estado'] ?? 'pendiente'
                    )
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'entregado' => 'success',
                        'ausente' => 'danger',
                        default => 'warning',
                    }),
            ])

            ->filters([

                SelectFilter::make('colegio')
                    ->options(Colegio::pluck('nombre', 'id'))
                    ->query(function (Builder $query, array $data) {
                        if (!filled($data['value'])) return;

                        $query->whereHas('beneficiario.gestiones.curso', function ($q) use ($data) {
                            $q->where('colegio_id', $data['value']);
                        });
                    }),

                SelectFilter::make('curso')
                    ->options(Curso::pluck('nombre', 'id'))
                    ->query(function (Builder $query, array $data) {
                        if (!filled($data['value'])) return;

                        $query->whereHas('beneficiario.gestiones', function ($q) use ($data) {
                            $q->where('curso_id', $data['value']);
                        });
                    }),
            ])

            ->recordActions([

                Action::make('entregar')
                    ->label('✔')
                    ->color('success')
                    ->action(fn ($record) =>
                        Entrega::updateOrCreate(
                            [
                                'subscripcion_id' => $record->id,
                                'fecha' => today(),
                            ],
                            [
                                'estado' => 'entregado',
                                'user_id' => auth()->user()->id,
                            ]
                        )
                    ),

                Action::make('ausente')
                    ->label('❌')
                    ->color('danger')
                    ->action(fn ($record) =>
                        Entrega::updateOrCreate(
                            [
                                'subscripcion_id' => $record->id,
                                'fecha' => today(),
                            ],
                            [
                                'estado' => 'ausente',
                                'user_id' => auth()->user()->id,
                            ]
                        )
                    ),
            ])

            ->toolbarActions([
                BulkAction::make('entregarTodos')
                    ->label('Entregar seleccionados')
                    ->action(function ($records) {

                        foreach ($records as $record) {
                            Entrega::updateOrCreate(
                                [
                                    'subscripcion_id' => $record->id,
                                    'fecha' => today(),
                                ],
                                [
                                    'estado' => 'entregado',
                                    'user_id' => auth()->user()->id,
                                ]
                            );
                        }
                    }),
            ]);
    }
}
