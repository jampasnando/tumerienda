<?php

namespace App\Filament\Resources\Beneficiarios\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class GestionesRelationManager extends RelationManager
{
    protected static string $relationship = 'gestiones';
    // protected static ?string $recordTitleAttribute = 'titulo';
    protected static ?string $title='Inscripciones';
    public function getTableHeading(): string|Htmlable|null
    {
        return 'INSCRIPCIONES';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('gestion_id')
                    ->label('Gestión')
                    ->relationship('gestion', 'anio')
                    ->required()
                    ->unique(
                        ignoreRecord: true,
                        modifyRuleUsing: function (Unique $rule) {
                            return $rule->where('beneficiario_id', $this->getOwnerRecord()->id);
                        }
                    ),

                Select::make('colegio_id')
                    ->label('Colegio')
                    ->relationship('colegio', 'nombre')
                    ->searchable()
                    ->required()
                    ->reactive(),

                Select::make('curso_id')
                    ->label('Curso')
                    ->relationship(
                        'curso',
                        'nombre',
                        fn ($query, $get) =>
                            $query->where('colegio_id', $get('colegio_id'))
                    )
                    ->preload()
                    ->searchable()
                    ->required(),

                Select::make('estado')
                    ->options([
                        'activo' => 'Activo',
                        'retirado' => 'Retirado',
                    ])
                    ->default('activo')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('gestion.anio')
                    ->label('Gestión')
                    ->sortable(),

                TextColumn::make('colegio.nombre')
                    ->label('Colegio')
                    ->searchable(),

                TextColumn::make('curso.nombre')
                    ->label('Curso'),

                TextColumn::make('estado')
                    ->badge()
                    ->colors([
                        'success' => 'activo',
                        'danger' => 'retirado',
                    ]),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make()
                ->label('Inscribir...'),
                // AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
    // 🔒 AUTO-ASIGNAR BENEFICIARIO
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['beneficiario_id'] = $this->getOwnerRecord()->id;

        return $data;
    }
}
