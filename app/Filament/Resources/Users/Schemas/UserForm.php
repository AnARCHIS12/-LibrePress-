<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Compte')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (mixed $state): bool => filled($state)),
                        Select::make('status')
                            ->options([
                                'active' => 'Actif',
                                'disabled' => 'Desactive',
                            ])
                            ->required()
                            ->default('active'),
                        Toggle::make('is_admin')
                            ->label('Administrateur legacy')
                            ->required(),
                        Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Localisation')
                    ->schema([
                        TextInput::make('locale')
                            ->required()
                            ->default('fr'),
                        TextInput::make('timezone')
                            ->required()
                            ->default('Europe/Podgorica'),
                        DateTimePicker::make('email_verified_at'),
                        DateTimePicker::make('last_login_at')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),
            ]);
    }
}
