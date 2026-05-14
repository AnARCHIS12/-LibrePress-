<?php

namespace App\Filament\Resources\Comments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Commentaire')
                    ->schema([
                        Select::make('content_id')
                            ->relationship('content', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('user_id')
                            ->numeric(),
                        TextInput::make('author_name')
                            ->required(),
                        TextInput::make('author_email_hash'),
                        Select::make('status')
                            ->options([
                                'pending' => 'En attente',
                                'approved' => 'Approuve',
                                'spam' => 'Spam',
                                'rejected' => 'Rejete',
                            ])
                            ->required()
                            ->default('pending'),
                        TextInput::make('reports_count')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_spam')
                            ->required(),
                        Textarea::make('body')
                            ->required()
                            ->rows(6)
                            ->columnSpanFull(),
                        TextInput::make('moderation_reason')
                            ->columnSpanFull(),
                        TextInput::make('ip_hash')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('user_agent_hash')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),
            ]);
    }
}
