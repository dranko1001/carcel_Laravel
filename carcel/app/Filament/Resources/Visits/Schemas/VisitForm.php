<?php

namespace App\Filament\Resources\Visits\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VisitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('prisoner_id')
                    ->required()
                    ->numeric(),
                TextInput::make('visitor_id')
                    ->required()
                    ->numeric(),
                TextInput::make('relationship')
                    ->required(),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('start_time')
                    ->required(),
                DateTimePicker::make('end_time')
                    ->required(),
            ]);
    }
}
