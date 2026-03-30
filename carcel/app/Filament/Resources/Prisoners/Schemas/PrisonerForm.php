<?php
namespace App\Filament\Resources\Prisoners\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PrisonerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('full_name')
                    ->label('Nombre Completo')
                    ->required()
                    ->maxLength(255),

                DatePicker::make('birth_date')
                    ->label('Fecha de Nacimiento')
                    ->required()
                    ->maxDate(now()->subYears(18)),

                DatePicker::make('entry_date')
                    ->label('Fecha de Ingreso')
                    ->required()
                    ->maxDate(now()),

                TextInput::make('crime')
                    ->label('Delito Cometido')
                    ->required()
                    ->maxLength(255),

                TextInput::make('assigned_cell')
                    ->label('Celda Asignada')
                    ->required()
                    ->maxLength(50),
            ]);
    }
}