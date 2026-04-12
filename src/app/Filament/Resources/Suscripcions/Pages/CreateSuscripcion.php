<?php

namespace App\Filament\Resources\Suscripcions\Pages;

use App\Filament\Resources\Suscripcions\SuscripcionResource;
use App\Models\Beneficiario;
use App\Models\Suscripcion;
use Filament\Resources\Pages\CreateRecord;

class CreateSuscripcion extends CreateRecord
{
    protected static string $resource = SuscripcionResource::class;
    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     $dias = $data['dias'];
    //     unset($data['dias']);

    //     $beneficiario = Beneficiario::find($data['beneficiario_id']);

    //     // VALIDACIONES
    //     if (!$beneficiario->colegioActivo) {
    //         throw new \Exception("El beneficiario no tiene colegio activo");
    //     }

    //     if (!$beneficiario->tutorActivo) {
    //         throw new \Exception("El beneficiario no tiene tutor activo");
    //     }

    //     foreach ($dias as $dia) {

    //         if (!$dia['menu_id']) continue;

    //         Suscripcion::updateOrCreate(
    //             [
    //                 'beneficiario_id' => $data['beneficiario_id'],
    //                 'fecha' => $dia['fecha'],
    //             ],
    //             [
    //                 'oferta_id' => $data['oferta_id'],
    //                 'menu_id' => $dia['menu_id'],
    //                 'colegio_id' => $beneficiario->colegioActivo->colegio_id,
    //                 'tutor_id' => $beneficiario->tutorActivo->tutor_id,
    //             ]
    //         );
    //     }

    //     // IMPORTANTE: evitar que Filament intente guardar 1 solo registro
    //     return [];
    // }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $selecciones = $data['calendario'] ?? [];
        unset($data['calendario']);

        $beneficiario = \App\Models\Beneficiario::find($data['beneficiario_id']);

        foreach ($selecciones as $fecha => $menuId) {

            if (!$menuId) continue;

            \App\Models\Suscripcion::updateOrCreate(
                [
                    'beneficiario_id' => $data['beneficiario_id'],
                    'fecha' => $fecha,
                ],
                [
                    'oferta_id' => $data['oferta_id'],
                    'menu_id' => $menuId,
                    'colegio_id' => $beneficiario->colegioActivo->colegio_id,
                    'tutor_id' => $beneficiario->tutorActivo->tutor_id,
                    'estado' => 'pendiente'
                ]
            );
        }

        return [];
    }
}
