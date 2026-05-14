<?php

namespace App\Filament\Resources\Contents\Schemas;

use App\Filament\Support\JsonState;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Publication')
                    ->schema([
                        Select::make('type')
                            ->options([
                                'page' => 'Page',
                                'post' => 'Article',
                            ])
                            ->required()
                            ->default('page'),
                        Select::make('status')
                            ->options([
                                'draft' => 'Brouillon',
                                'review' => 'Relecture',
                                'scheduled' => 'Planifie',
                                'published' => 'Publie',
                                'archived' => 'Archive',
                            ])
                            ->required()
                            ->default('draft'),
                        Select::make('author_id')
                            ->relationship('author', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('locale')
                            ->required()
                            ->maxLength(12)
                            ->default('fr'),
                        DateTimePicker::make('published_at'),
                        DateTimePicker::make('scheduled_at'),
                    ])
                    ->columns(3),
                Section::make('Identite')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('excerpt')
                            ->rows(3)
                            ->columnSpanFull(),
                        Select::make('terms')
                            ->relationship('terms', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Editeur bloc')
                    ->schema([
                        Builder::make('editor_blocks')
                            ->label('Blocs')
                            ->addActionLabel('Ajouter un bloc')
                            ->blockIcons()
                            ->collapsible()
                            ->cloneable()
                            ->reorderableWithDragAndDrop()
                            ->blocks([
                                Block::make('heading')
                                    ->label('Titre')
                                    ->schema([
                                        TextInput::make('text')
                                            ->label('Texte')
                                            ->required(),
                                        Select::make('level')
                                            ->label('Niveau')
                                            ->options([
                                                2 => 'H2',
                                                3 => 'H3',
                                                4 => 'H4',
                                            ])
                                            ->default(2)
                                            ->required(),
                                    ])
                                    ->columns(2),
                                Block::make('paragraph')
                                    ->label('Texte')
                                    ->schema([
                                        Textarea::make('text')
                                            ->label('Texte')
                                            ->rows(6)
                                            ->required(),
                                    ]),
                                Block::make('image')
                                    ->label('Image')
                                    ->schema([
                                        TextInput::make('src')
                                            ->label('URL')
                                            ->url()
                                            ->required(),
                                        TextInput::make('alt')
                                            ->label('Texte alternatif')
                                            ->required(),
                                    ]),
                                Block::make('gallery')
                                    ->label('Galerie')
                                    ->schema([
                                        Repeater::make('images')
                                            ->label('Images')
                                            ->schema([
                                                TextInput::make('src')
                                                    ->label('URL')
                                                    ->url()
                                                    ->required(),
                                                TextInput::make('alt')
                                                    ->label('Texte alternatif')
                                                    ->required(),
                                            ])
                                            ->columns(2),
                                    ]),
                                Block::make('button')
                                    ->label('Bouton')
                                    ->schema([
                                        TextInput::make('label')
                                            ->required()
                                            ->default('Lire'),
                                        TextInput::make('url')
                                            ->required()
                                            ->default('/'),
                                    ])
                                    ->columns(2),
                                Block::make('columns')
                                    ->label('Colonnes')
                                    ->schema([
                                        Repeater::make('columns')
                                            ->label('Colonnes')
                                            ->simple(
                                                Textarea::make('text')
                                                    ->rows(5)
                                                    ->required(),
                                            )
                                            ->defaultItems(2)
                                            ->minItems(2)
                                            ->maxItems(4),
                                    ]),
                                Block::make('embed')
                                    ->label('Embed')
                                    ->schema([
                                        TextInput::make('url')
                                            ->url()
                                            ->required(),
                                    ]),
                                Block::make('code')
                                    ->label('Code')
                                    ->schema([
                                        TextInput::make('language')
                                            ->default('text')
                                            ->required(),
                                        CodeEditor::make('code')
                                            ->language(Language::JavaScript)
                                            ->wrap()
                                            ->required()
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                                Block::make('quote')
                                    ->label('Citation')
                                    ->schema([
                                        Textarea::make('text')
                                            ->rows(4)
                                            ->required(),
                                        TextInput::make('cite')
                                            ->label('Source'),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('SEO et relations')
                    ->schema([
                        TextInput::make('parent_id')
                            ->numeric(),
                        TextInput::make('translation_group_id'),
                        CodeEditor::make('meta')
                            ->helperText('JSON libre pour OpenGraph, canonical, robots, etc.')
                            ->language(Language::Json)
                            ->formatStateUsing(fn (mixed $state): string => JsonState::encode($state))
                            ->dehydrateStateUsing(fn (mixed $state): array => JsonState::decode($state))
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }
}
