<?php

namespace App\Filament\Resources\Terms\Schemas;

use App\Filament\Support\JsonState;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TermForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Terme')
                    ->schema([
                        Select::make('taxonomy_id')
                            ->relationship('taxonomy', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('parent_id')
                            ->numeric(),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(120),
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
