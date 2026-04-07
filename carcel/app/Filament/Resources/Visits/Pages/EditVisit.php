<?php

namespace App\Filament\Resources\Visits\Pages;

use App\Filament\Resources\Visits\VisitResource;
use App\Models\Visit;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Carbon;

class EditVisit extends EditRecord
{
    protected static string $resource = VisitResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    /**
     * Al editar, excluimos el ID actual para no comparar la visita consigo misma.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $start = Carbon::parse($data['start_time']);
        $end = Carbon::parse($data['end_time']);

        if (Visit::prisonerHasScheduleConflict(
            (int) $data['prisoner_id'],
            $start,
            $end,
            $this->getRecord()->getKey()
        )) {
            Notification::make()
                ->title('Horario no disponible')
                ->body('Este prisionero ya tiene otra visita que se cruza con el horario elegido.')
                ->danger()
                ->persistent()
                ->send();

            $this->halt();
        }

        return $data;
    }
}