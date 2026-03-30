<?php
namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre Completo')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Correo Electrónico')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                TextInput::make('identification_number')
                    ->label('Número de Identificación')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->numeric()
                    ->maxLength(20),

                TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->required(fn(string $operation) => $operation === 'create')
                    ->dehydrateStateUsing(fn($state) => !empty($state) ? bcrypt($state) : null)
                    ->dehydrated(fn($state) => !empty($state))
                    ->hint('Dejar en blanco para no cambiar la contraseña'),

                Select::make('role')
                    ->label('Rol')
                    ->options([
                        'admin' => 'Administrador',
                        'guard' => 'Guardia',
                    ])
                    ->default('guard')
                    ->required(),

                Toggle::make('is_active')
                    ->label('Activo')
                    ->default(true)
                    ->helperText('Desactivar impide el acceso al sistema'),
            ]);
    }
}