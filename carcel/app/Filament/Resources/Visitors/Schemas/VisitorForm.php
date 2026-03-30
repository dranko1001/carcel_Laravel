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
                    ->required(),
                TextInput::make('identification_number')
                    ->required(),
            ]);
    }
}
