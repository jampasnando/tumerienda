<?php

namespace App\Filament\Resources\BeneficiarioPlans\Pages;

use App\Filament\Resources\BeneficiarioPlans\BeneficiarioPlanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBeneficiarioPlans extends ListRecords
{
    protected static string $resource = BeneficiarioPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
