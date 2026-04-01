<?php
namespace App\Filament\Resources\Visits\Pages;

use App\Filament\Resources\Visits\VisitResource;
use App\Models\Visit;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateVisit extends CreateRecord
{
    protected static string $resource = VisitResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $existe = Visit::where('prisoner_id', $data['prisoner_id'])
            ->where('start_time', $data['start_time'])
            ->whereIn('status', ['pendiente', 'completada'])
            ->exists();

        if ($existe) {
            Notification::make()
                ->title('Horario no disponible')
                ->body('Este prisionero ya tiene una visita activa registrada a esa hora.')
                ->danger()
                ->persistent()
                ->send();

            $this->halt();
        }

        $data['status'] = 'pendiente';

        return $data;
    }
}