<?php

namespace App\Filament\Pages;

use App\Models\Beneficiario;
use App\Models\Suscripcion;
use Carbon\Carbon;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;


class CalendarioProduccion extends Page
{
    protected string $view = 'filament.pages.calendario-produccion';
    // protected static  $navigationIcon = 'heroicon-o-user';
    // protected string $view = 'filament.pages.calendario-beneficiario';
    // protected static  $navigationGroup = 'Operaciones';

    public $mes;
    public $anio;

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

    // public function getSuscripciones()
    // {
    //     return Suscripcion::with('menu')
    //         ->where('beneficiario_id', $this->beneficiario_id)
    //         ->whereMonth('fecha', $this->mes)
    //         ->whereYear('fecha', $this->anio)
    //         ->get()
    //         ->keyBy(fn ($s) => $s->fecha);
    // }
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
