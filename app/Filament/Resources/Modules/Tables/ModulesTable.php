<?php

namespace App\Filament\Resources\Modules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ModulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('version')
                    ->searchable(),
                IconColumn::make('enabled')
                    ->boolean(),
                TextColumn::make('installed_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('enabled_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('enabled'),
            ])
            ->recordActions([
                Action::make('enable')
                    ->label('Activer')
                    ->visible(fn ($record): bool => ! $record->enabled)
                    ->action(fn ($record) => $record->update([
                        'enabled' => true,
                        'enabled_at' => now(),
                    ])),
                Action::make('disable')
                    ->label('Desactiver')
                    ->color('warning')
                    ->visible(fn ($record): bool => $record->enabled)
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update([
                        'enabled' => false,
                        'enabled_at' => null,
                    ])),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
