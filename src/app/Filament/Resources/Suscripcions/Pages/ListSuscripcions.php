<?php

namespace App\Filament\Resources\Suscripcions\Pages;

use App\Filament\Resources\Suscripcions\SuscripcionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListSuscripcions extends ListRecords
{
    protected static string $resource = SuscripcionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    public function getTabs(): array
{
    return [



        'hoy' => Tab::make()
            ->query(fn ($query) => $query->whereDate('fecha', now())),

        'esta_semana' => Tab::make()
            ->query(fn ($query) =>
                $query->whereBetween('fecha', [now()->startOfWeek(), now()->endOfWeek()])
            ),

        'futuras' => Tab::make()
            ->query(fn ($query) => $query->whereDate('fecha', '>', now())),
        'todas' => Tab::make(),
    ];
}
}
