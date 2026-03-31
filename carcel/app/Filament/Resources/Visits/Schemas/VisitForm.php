<?php
namespace App\Filament\Resources\Visits\Schemas;

use App\Models\Prisoner;
use App\Models\Visitor;
use App\Models\Visit;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VisitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('prisoner_id')
                    ->label('Prisionero')
                    ->options(Prisoner::all()->pluck('full_name', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('visitor_id')
                    ->label('Visitante')
                    ->options(Visitor::all()->pluck('full_name', 'id'))
                    ->searchable()
                    ->required(),

                TextInput::make('relationship')
                    ->label('Relación con el Prisionero')
                    ->required()
                    ->maxLength(100),

                Select::make('user_id')
                    ->label('Guardia que Aprueba')
                    ->options(User::where('role', 'guard')->where('is_active', true)->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                DateTimePicker::make('start_time')
                    ->label('Hora de Inicio')
                    ->required()
                    ->seconds(false)
                    ->minutesStep(30)
                    ->minDate(now()->startOfDay())
                    ->maxDate(now()->addMonths(3))
                    ->rules([
                        function () {
                            return function (string $attribute, $value, $fail) {
                                $date = \Carbon\Carbon::parse($value);

                                if ($date->dayOfWeek !== \Carbon\Carbon::SUNDAY) {
                                    $fail('Las visitas solo se permiten los domingos.');
                                    return;
                                }

                                $timeInMinutes = (int) $date->format('H') * 60 + (int) $date->format('i');
                                if ($timeInMinutes < 840 || $timeInMinutes > 1020) {
                                    $fail('El horario de visitas es de 14:00 a 17:00.');
                                    return;
                                }

                                if ($date->isPast()) {
                                    $fail('No se puede registrar una visita en una fecha pasada.');
                                    return;
                                }

                                if ($date->isAfter(now()->addMonths(3))) {
                                    $fail('No se puede registrar una visita con más de 3 meses de anticipación.');
                                    return;
                                }
                            };
                        },
                    ]),

                DateTimePicker::make('end_time')
                    ->label('Hora de Fin')
                    ->required()
                    ->seconds(false)
                    ->minutesStep(30)
                    ->rules([
                        function () {
                            return function (string $attribute, $value, $fail) {
                                $date = \Carbon\Carbon::parse($value);
                                $timeInMinutes = (int) $date->format('H') * 60 + (int) $date->format('i');

                                if ($timeInMinutes < 840 || $timeInMinutes > 1020) {
                                    $fail('El horario de visitas es de 14:00 a 17:00.');
                                }
                            };
                        },
                    ])
                    ->after('start_time'),
            ]);
    }
}