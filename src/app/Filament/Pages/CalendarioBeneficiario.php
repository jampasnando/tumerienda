<?php
namespace App\Filament\Pages;

use Filament\Pages\Page;
use Carbon\Carbon;
use App\Models\Suscripcion;
use App\Models\Beneficiario;

class CalendarioBeneficiario extends Page
{
    // protected static  $navigationIcon = 'heroicon-o-user';
    protected string $view = 'filament.pages.calendario-beneficiario';
    // protected static  $navigationGroup = 'Operaciones';

    public $beneficiario_id;
    public $mes;
    public $anio;

    public function mount()
    {
        $this->mes = now()->month;
        $this->anio = now()->year;
    }

    public function getBeneficiario()
    {
        return Beneficiario::find($this->beneficiario_id);
    }

    public function getDias()
    {
        $inicio = \Carbon\Carbon::create($this->anio, $this->mes, 1);
        $fin = $inicio->copy()->endOfMonth();

        $dias = [];

        // 🔥 Día de la semana (1=Lunes, 5=Viernes)
        $primerDiaSemana = $inicio->dayOfWeekIso;

        // 👉 agregar espacios vacíos antes del día 1
        for ($i = 1; $i < $primerDiaSemana; $i++) {
            $dias[] = null;
        }

        while ($inicio->lte($fin)) {

            if (!$inicio->isSunday()) {
                $dias[] = $inicio->copy();
            }

            $inicio->addDay();
        }

        return $dias;
    }

    public function getSuscripciones()
    {
        return Suscripcion::with('menu')
            ->where('beneficiario_id', $this->beneficiario_id)
            ->whereMonth('fecha', $this->mes)
            ->whereYear('fecha', $this->anio)
            ->get()
            ->keyBy(fn ($s) => $s->fecha);
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
    }
}
