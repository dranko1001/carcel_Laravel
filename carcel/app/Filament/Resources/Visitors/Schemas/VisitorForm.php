<?php
namespace App\Filament\Resources\Visitors\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VisitorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('full_name')
                    ->label('Nombre Completo')
                    ->required()
                    ->maxLength(255),

                TextInput::make('identification_number')
                    ->label('Número de Identificación')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(20)
                    ->numeric(),
            ]);
    }
}