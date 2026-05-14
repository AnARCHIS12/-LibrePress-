<?php

namespace App\Filament\Resources\MenuItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MenuItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Element de navigation')
                    ->schema([
                        Select::make('menu_id')
                            ->relationship('menu', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('parent_id')
                            ->numeric(),
                        TextInput::make('label')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('url')
                            ->required()
                            ->maxLength(500),
                        TextInput::make('sort_order')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Toggle::make('new_tab')
                            ->label('Ouvrir dans un nouvel onglet')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
