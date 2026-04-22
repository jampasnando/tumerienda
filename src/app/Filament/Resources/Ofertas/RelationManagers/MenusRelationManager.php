<?php

namespace App\Filament\Resources\Ofertas\RelationManagers;

use App\Filament\Resources\Menus\MenuResource;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MenusRelationManager extends RelationManager
{
    protected static string $relationship = 'menus';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre'),
                Textarea::make('descripcion')
                    ->columnSpanFull(),
                TextInput::make('costo')
                    ->numeric(),
                TextInput::make('precio')
                    ->numeric(),
                Toggle::make('activo'),
                TextInput::make('foto'),
                Textarea::make('ingredientes')
                    ->columnSpanFull(),
                Textarea::make('preparacion')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
            ->columns([
                TextColumn::make('tipo'),
                TextColumn::make('nombre')
                    ->url(fn ($record) =>
                        MenuResource::getUrl('view', [
                            'record' => $record,
                        ])
                    )
                    ->searchable(),
                TextColumn::make('costo_total')
                    ->label('Costo')
                    ->numeric()
                    ->sortable(),
                // TextColumn::make('precio')
                //     ->numeric()
                //     ->sortable(),
                IconColumn::make('activo')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('foto')
                    ->disk('public')
                    ->url(fn ($record) => $record->foto ? asset('storage/' . $record->foto) : null)
                    ->openUrlInNewTab()
                    ->circular()
                    ->searchable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                // CreateAction::make(),
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->label('Agregar menú'),
            ])
            ->recordActions([
                // EditAction::make(),
                DetachAction::make(),
                // DeleteAction::make(),
                // ForceDeleteAction::make(),
                // RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                    // DeleteBulkAction::make(),
                    // ForceDeleteBulkAction::make(),
                    // RestoreBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
