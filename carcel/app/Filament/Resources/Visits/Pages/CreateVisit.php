<?php

namespace App\Filament\Resources\Visits\Pages;

use App\Filament\Resources\Visits\VisitResource;
use App\Models\Visit;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;

class CreateVisit extends CreateRecord
{
    protected static string $resource = VisitResource::class;

    /**
     * Última capa de validación antes de guardar: evita solapes aunque alguien salte reglas del formulario.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $start = Carbon::parse($data['start_time']);
        $end = Carbon::parse($data['end_time']);

        if (Visit::prisonerHasScheduleConflict((int) $data['prisoner_id'], $start, $end, null)) {
            Notification::make()
                ->title('Horario no disponible')
                ->body('Este prisionero ya tiene otra visita en ese tramo (o se cruza con él). Puedes agendar otro domingo u otra hora dentro de 14:00–17:00 si no hay cruce.')
                ->danger()
                ->persistent()
                ->send();

            $this->halt();
        }

        // Las visitas nuevas entran como pendientes hasta que pasen o se cancelen desde la tabla
        $data['status'] = 'pendiente';

        return $data;
    }
}