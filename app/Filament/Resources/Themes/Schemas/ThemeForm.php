<?php

namespace App\Filament\Resources\Themes\Schemas;

use App\Filament\Support\JsonState;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ThemeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Theme')
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('slug')
                            ->required(),
                        TextInput::make('version')
                            ->required(),
                        Toggle::make('enabled')
                            ->required(),
                        CodeEditor::make('config')
                            ->language(Language::Json)
                            ->formatStateUsing(fn (mixed $state): string => JsonState::encode($state))
                            ->dehydrateStateUsing(fn (mixed $state): array => JsonState::decode($state))
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
