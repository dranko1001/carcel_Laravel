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
                    ->validationMessages([
                        'unique' => 'Ya existe un visitante con este número de identificación. Revise el listado o use el registro existente.',
                    ])
                    ->maxLength(20)
                    ->numeric(),
            ]);
    }
}