<?php

namespace App\Filament\Resources\Taxonomies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TaxonomyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Taxonomie')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(120),
                        TextInput::make('owner')
                            ->required()
                            ->default('core')
                            ->maxLength(80),
                    ])
                    ->columns(3),
            ]);
    }
}
