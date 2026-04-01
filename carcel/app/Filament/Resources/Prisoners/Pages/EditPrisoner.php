<?php

namespace App\Filament\Resources\Prisoners\Pages;

use App\Filament\Resources\Prisoners\PrisonerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPrisoner extends EditRecord
{
    protected static string $resource = PrisonerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
