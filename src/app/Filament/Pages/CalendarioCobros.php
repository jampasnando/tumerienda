<?php

namespace App\Filament\Pages;

use App\Models\Beneficiario;
use App\Models\Cobrosqr;
use App\Models\Plan;
use Carbon\Carbon;
use Filament\Pages\Page;

class CalendarioCobros extends Page
{
    protected string $view = 'filament.pages.calendario-cobros';

    public $mes;
    public $anio;

    public function mount()
    {
        $this->mes = now()->month;
        $this->anio = now()->year;
    }


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
        return Cobrosqr::query()
            ->select([
                'id',
                'alias',
                'nombreCliente',
                'monto',
                'fechareg',
            ])
            ->whereMonth('fechareg', $this->mes)
            ->whereYear('fechareg', $this->anio)
            ->orderBy('fechareg')
            ->get()
            ->map(function ($cobro) {
                $cobro->beneficiario_nombre = null;
                $cobro->plan_nombre = null;

                if (preg_match('/^Susc(\d+)_Benef(\d+)_/i', (string) ($cobro->alias ?? ''), $matches)) {
                    $planId = (int) $matches[1];
                    $beneficiarioId = (int) $matches[2];

                    $beneficiario = Beneficiario::find($beneficiarioId);
                    $plan = Plan::find($planId);

                    $cobro->beneficiario_nombre = $beneficiario
                        ? trim(($beneficiario->nombre ?? '') . ' ' . ($beneficiario->apellidos ?? ''))
                        : null;

                    $cobro->plan_nombre = $plan?->nombre;
                }

                return $cobro;
            })
            ->groupBy(function ($cobro) {
                return Carbon::parse($cobro->fechareg)->format('Y-m-d');
            });
    }
    public function prevMes()
    {
        $date = Carbon::create($this->anio, $this->mes)->subMonth();
        $this->mes = $date->month;
        $this->anio = $date->year;
    }

    public function nextMes()
    {
        $date = Carbon::create($this->anio, $this->mes)->addMonth();
        $this->mes = $date->month;
        $this->anio = $date->year;
        // $this->emit('$refresh');
    }
}
