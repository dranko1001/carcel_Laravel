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
use Illuminate\Validation\ValidationException;

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
                    ->rules([
                        function () {
                            return function (string $attribute, $value, $fail) {
                                $date = \Carbon\Carbon::parse($value);

                                // Debe ser domingo
                                if ($date->dayOfWeek !== \Carbon\Carbon::SUNDAY) {
                                    $fail('Las visitas solo se permiten los domingos.');
                                    return;
                                }

                                // Debe estar entre 14:00 y 17:00
                                $hour = (int) $date->format('H');
                                $minute = (int) $date->format('i');
                                $timeInMinutes = $hour * 60 + $minute;

                                if ($timeInMinutes < 840 || $timeInMinutes > 1020) {
                                    $fail('El horario de visitas es de 14:00 a 17:00.');
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

                                $hour = (int) $date->format('H');
                                $minute = (int) $date->format('i');
                                $timeInMinutes = $hour * 60 + $minute;

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