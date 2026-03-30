<?php

namespace App\Filament\Resources\Prisoners\Pages;

use App\Filament\Resources\Prisoners\PrisonerResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePrisoner extends CreateRecord
{
    protected static string $resource = PrisonerResource::class;
}
