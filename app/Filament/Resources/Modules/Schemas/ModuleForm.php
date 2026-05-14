<?php

namespace App\Filament\Resources\Modules\Schemas;

use App\Filament\Support\JsonState;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Module')
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('slug')
                            ->required(),
                        TextInput::make('version')
                            ->required(),
                        Toggle::make('enabled')
                            ->required(),
                        DateTimePicker::make('installed_at'),
                        DateTimePicker::make('enabled_at'),
                        CodeEditor::make('manifest')
                            ->language(Language::Json)
                            ->formatStateUsing(fn (mixed $state): string => JsonState::encode($state))
                            ->dehydrateStateUsing(fn (mixed $state): array => JsonState::decode($state))
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
            ]);
    }
}
