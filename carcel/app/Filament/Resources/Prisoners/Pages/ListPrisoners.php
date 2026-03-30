<?php

namespace App\Filament\Resources\Prisoners\Pages;

use App\Filament\Resources\Prisoners\PrisonerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPrisoners extends ListRecords
{
    protected static string $resource = PrisonerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
