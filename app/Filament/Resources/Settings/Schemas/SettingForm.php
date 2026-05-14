<?php

namespace App\Filament\Resources\Settings\Schemas;

use App\Filament\Support\JsonState;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Parametre')
                    ->schema([
                        TextInput::make('key')
                            ->required(),
                        Select::make('type')
                            ->options([
                                'string' => 'Texte',
                                'bool' => 'Booleen',
                                'int' => 'Entier',
                                'json' => 'JSON',
                            ])
                            ->required()
                            ->default('string'),
                        TextInput::make('scope')
                            ->required()
                            ->default('site'),
                        Toggle::make('autoload')
                            ->required(),
                        CodeEditor::make('value')
                            ->language(Language::Json)
                            ->formatStateUsing(fn (mixed $state): string => JsonState::encode($state))
                            ->dehydrateStateUsing(fn (mixed $state): array => JsonState::decode($state))
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
