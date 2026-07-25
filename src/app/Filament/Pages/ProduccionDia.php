<?php

namespace App\Filament\Pages;

use App\Models\Suscripcion;
use Filament\Pages\Page;

class ProduccionDia extends Page
{
    protected string $view = 'filament.pages.produccion-dia';
    public $fecha;
    public function mount()
    {
        $this->fecha=request('fecha');
    }
    public function getProduccion()
    {
        return Suscripcion::with([
                'menu',
                'beneficiario.nombrecolegioActivo.colegio'
            ])
            ->whereDate('fecha', $this->fecha)
            ->get()
            ->groupBy(function ($suscripcion) {

                return $suscripcion->beneficiario
                        ->nombrecolegioActivo
                        ?->colegio
                        ?->nombre
                    ?? 'Sin colegio';

            })
            ->map(function ($colegio) {

                return $colegio->groupBy('beneficiario_id');

            });
    }
}
