<?php

namespace App\Filament\Resources\Contents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->badge()
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('author.name')
                    ->searchable(),
                TextColumn::make('locale')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('scheduled_at')
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
                SelectFilter::make('type')
                    ->options([
                        'page' => 'Pages',
                        'post' => 'Articles',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Brouillons',
                        'review' => 'En relecture',
                        'scheduled' => 'Planifies',
                        'published' => 'Publies',
                        'archived' => 'Archives',
                    ]),
                SelectFilter::make('author')
                    ->relationship('author', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                Action::make('publish')
                    ->label('Publier')
                    ->visible(fn ($record): bool => $record->status !== 'published')
                    ->action(fn ($record) => $record->update([
                        'status' => 'published',
                        'published_at' => $record->published_at ?? now(),
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
