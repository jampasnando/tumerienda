<?php

namespace App\Filament\Resources\BeneficiarioPlans;

use App\Filament\Resources\BeneficiarioPlans\Pages\CreateBeneficiarioPlan;
use App\Filament\Resources\BeneficiarioPlans\Pages\EditBeneficiarioPlan;
use App\Filament\Resources\BeneficiarioPlans\Pages\ListBeneficiarioPlans;
use App\Filament\Resources\BeneficiarioPlans\Schemas\BeneficiarioPlanForm;
use App\Filament\Resources\BeneficiarioPlans\Tables\BeneficiarioPlansTable;
use App\Models\BeneficiarioPlan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BeneficiarioPlanResource extends Resource
{
    protected static ?string $model = BeneficiarioPlan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'beneficario.nombre';

    public static function form(Schema $schema): Schema
    {
        return BeneficiarioPlanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BeneficiarioPlansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBeneficiarioPlans::route('/'),
            'create' => CreateBeneficiarioPlan::route('/create'),
            'edit' => EditBeneficiarioPlan::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
