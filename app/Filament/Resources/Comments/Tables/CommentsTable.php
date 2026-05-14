<?php

namespace App\Filament\Resources\Comments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('content.title')
                    ->searchable(),
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('author_name')
                    ->searchable(),
                TextColumn::make('author_email_hash')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('ip_hash')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('user_agent_hash')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('reports_count')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_spam')
                    ->boolean(),
                TextColumn::make('moderation_reason')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'En attente',
                        'approved' => 'Approuves',
                        'spam' => 'Spam',
                        'rejected' => 'Rejetes',
                    ]),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Approuver')
                    ->visible(fn ($record): bool => $record->status !== 'approved')
                    ->action(fn ($record) => $record->update([
                        'status' => 'approved',
                        'is_spam' => false,
                        'moderation_reason' => null,
                    ])),
                Action::make('spam')
                    ->label('Spam')
                    ->color('danger')
                    ->visible(fn ($record): bool => $record->status !== 'spam')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update([
                        'status' => 'spam',
                        'is_spam' => true,
                        'moderation_reason' => 'Marque comme spam depuis Filament',
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
