<?php

namespace App\Filament\Pages;

use App\Models\Beneficiario;
use App\Models\BeneficiarioColegio;
use App\Models\Suscripcion;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;


class CalendarioBeneficiario extends Page
{
    protected string $view = 'filament.pages.calendario-beneficiario';
    // protected static  $navigationIcon = 'heroicon-o-user';
    // protected string $view = 'filament.pages.calendario-beneficiario';
    // protected static  $navigationGroup = 'Operaciones';

    public $mes;
    public $anio;
    public ?int $beneficiario_id = null;
    public ?string $beneficiarioNombre = null;
    public function mount()
    {
        $this->mes = now()->month;
        $this->anio = now()->year;
    }

    // public function getBeneficiario()
    // {
    //     return Beneficiario::find($this->beneficiario_id);
    // }

    public function getDias()
    {
        $inicio = Carbon::create($this->anio, $this->mes, 1);
        $fin = $inicio->copy()->endOfMonth();

        $dias = [];

        // 1=Lunes ... 7=Domingo
        $primerDiaSemana = $inicio->dayOfWeekIso;

        // Espacios antes del día 1
        for ($i = 1; $i < $primerDiaSemana; $i++) {
            $dias[] = null;
        }

        while ($inicio->lte($fin)) {
            $dias[] = $inicio->copy();
            $inicio->addDay();
        }

        // Completar la última semana
        while (count($dias) % 7 != 0) {
            $dias[] = null;
        }

        return $dias;
    }

    protected function getHeaderActions(): array
    {
        return [

            Action::make('beneficiario')
                ->label($this->beneficiarioNombre ?: 'Seleccionar beneficiario')
                ->icon('heroicon-o-user')
                ->modalHeading('Seleccionar beneficiario')
                ->form([

                    Select::make('beneficiario_id')
                        ->label('Beneficiario')
                        ->searchable()
                        ->preload()
                        ->required()

                        ->getSearchResultsUsing(function (string $search) {

                            return BeneficiarioColegio::query()

                                ->where('activo', 1)

                                ->with('beneficiario')

                                ->where(function ($query) use ($search) {

                                    $query->where('codigo', 'like', "%{$search}%")

                                        ->orWhereHas('beneficiario', function ($q) use ($search) {

                                            $q->where('nombre', 'like', "%{$search}%")
                                                ->orWhere('apellidos', 'like', "%{$search}%");
                                        });
                                })

                                ->limit(50)

                                ->get()

                                ->mapWithKeys(function ($item) {

                                    return [

                                        $item->beneficiario_id =>

                                        $item->codigo
                                            . ' - '
                                            . $item->beneficiario->nombre
                                            . ' '
                                            . $item->beneficiario->apellidos

                                    ];
                                })

                                ->toArray();
                        })

                        ->getOptionLabelUsing(function ($value) {

                            $item = BeneficiarioColegio::with('beneficiario')

                                ->where('beneficiario_id', $value)
                                ->where('activo', 1)
                                ->first();

                            if (!$item) {
                                return null;
                            }

                            return $item->codigo
                                . ' - '
                                . $item->beneficiario->nombre
                                . ' '
                                . $item->beneficiario->apellidos;
                        })

                        ->default($this->beneficiario_id)

                ])

                ->action(function (array $data) {

                    $this->beneficiario_id = $data['beneficiario_id'];

                    $item = BeneficiarioColegio::with('beneficiario')
                        ->where('beneficiario_id', $this->beneficiario_id)
                        ->where('activo', 1)
                        ->first();

                    $this->beneficiarioNombre =
                        $item->codigo
                        . ' - '
                        . $item->beneficiario->nombre
                        . ' '
                        . $item->beneficiario->apellidos;
                })

        ];
    }

    public function getResumen()
    {
        return Suscripcion::query()
            ->join('menus', 'menus.id', '=', 'suscripciones.menu_id')
            ->select(
                'suscripciones.fecha',
                'suscripciones.menu_id',
                'menus.nombre as menu_nombre',
                DB::raw('COUNT(*) as cantidad')
            )
            ->whereMonth('suscripciones.fecha', $this->mes)
            ->when($this->beneficiario_id, function ($query) {
                $query->where('beneficiario_id', $this->beneficiario_id);
            })
            ->whereYear('suscripciones.fecha', $this->anio)
            ->groupBy(
                'suscripciones.fecha',
                'suscripciones.menu_id',
                'menus.nombre'
            )
            ->orderBy('suscripciones.fecha')
            ->orderBy('menus.nombre')
            ->get()
            ->groupBy('fecha');
    }
    public function prevMes()
    {
        $date = Carbon::create($this->anio, $this->mes)->subMonth();
        $this->mes = $date->month;
        $this->anio = $date->year;
        $this->emit('$refresh');
    }

    public function nextMes()
    {
        $date = Carbon::create($this->anio, $this->mes)->addMonth();
        $this->mes = $date->month;
        $this->anio = $date->year;
        $this->emit('$refresh');
    }
}
