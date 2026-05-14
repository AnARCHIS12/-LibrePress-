<?php

namespace App\Filament\Resources\Media\Schemas;

use App\Filament\Support\JsonState;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Fichier')
                    ->schema([
                        Select::make('disk')
                            ->options([
                                'public' => 'Public',
                                'local' => 'Local',
                            ])
                            ->required()
                            ->default('public'),
                        TextInput::make('path')
                            ->required()
                            ->maxLength(500),
                        TextInput::make('mime_type')
                            ->required()
                            ->maxLength(120),
                        TextInput::make('size')
                            ->required()
                            ->numeric()
                            ->minValue(0),
                        TextInput::make('width')
                            ->numeric()
                            ->minValue(0),
                        TextInput::make('height')
                            ->numeric()
                            ->minValue(0),
                    ])
                    ->columns(3),
                Section::make('Accessibilite et variantes')
                    ->schema([
                        TextInput::make('alt')
                            ->label('Texte alternatif')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('caption')
                            ->label('Legende')
                            ->rows(3)
                            ->columnSpanFull(),
                        TextInput::make('hash')
                            ->required()
                            ->maxLength(128),
                        TextInput::make('created_by')
                            ->numeric(),
                        CodeEditor::make('meta')
                            ->language(Language::Json)
                            ->formatStateUsing(fn (mixed $state): string => JsonState::encode($state))
                            ->dehydrateStateUsing(fn (mixed $state): array => JsonState::decode($state))
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
