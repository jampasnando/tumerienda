<?php

namespace App\Filament\Resources\BeneficiarioPlans\Pages;

use App\Filament\Resources\BeneficiarioPlans\BeneficiarioPlanResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditBeneficiarioPlan extends EditRecord
{
    protected static string $resource = BeneficiarioPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
