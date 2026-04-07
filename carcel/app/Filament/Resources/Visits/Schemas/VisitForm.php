<?php

namespace App\Filament\Resources\Visits\Schemas;

use App\Models\Prisoner;
use App\Models\User;
use App\Models\Visit;
use App\Models\Visitor;
use Closure;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;

class VisitForm
{
    /**
     * Formulario de visitas para el panel de guardias.
     * Las reglas de negocio (domingo 14:00–17:00, etc.) también se refuerzan al guardar en CreateVisit/EditVisit.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('prisoner_id')
                    ->label('Prisionero')
                    ->options(Prisoner::query()->orderBy('full_name')->pluck('full_name', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('visitor_id')
                    ->label('Visitante')
                    ->options(Visitor::query()->orderBy('full_name')->pluck('full_name', 'id'))
                    ->searchable()
                    ->required(),

                TextInput::make('relationship')
                    ->label('Relación con el prisionero')
                    ->required()
                    ->maxLength(100),

                // Guardia que aprueba la visita (por defecto el usuario conectado si es guardia)
                Select::make('user_id')
                    ->label('Guardia que aprueba')
                    ->options(
                        User::query()
                            ->where('role', 'guard')
                            ->where('is_active', true)
                            ->orderBy('name')
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required()
                    ->default(fn (): ?int => auth()->check() && auth()->user()->role === 'guard'
                        ? auth()->id()
                        : null),

                DateTimePicker::make('start_time')
                    ->label('Hora de inicio')
                    ->required()
                    ->seconds(false)
                    ->minutesStep(15)
                    ->minDate(now()->startOfDay())
                    ->maxDate(now()->addMonths(3))
                    ->rules([
                        fn (): Closure => function (string $attribute, $value, Closure $fail): void {
                            if ($value === null || $value === '') {
                                return;
                            }

                            $date = Carbon::parse($value);

                            // Solo domingos
                            if ($date->dayOfWeek !== Visit::ALLOWED_DAY_OF_WEEK) {
                                $fail('Las visitas solo se permiten los domingos.');

                                return;
                            }

                            // Entre 14:00 y 17:00
                            if (! Visit::isWithinSundayWindow($date)) {
                                $fail('El horario permitido es de 14:00 a 17:00.');

                                return;
                            }

                            if ($date->isPast()) {
                                $fail('No se puede registrar una visita en una fecha u hora que ya pasó.');

                                return;
                            }
                        },
                    ]),

                DateTimePicker::make('end_time')
                    ->label('Hora de fin')
                    ->required()
                    ->seconds(false)
                    ->minutesStep(15)
                    ->rules([
                        fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get): void {
                            if ($value === null || $value === '') {
                                return;
                            }

                            $startRaw = $get('start_time');
                            if (! $startRaw) {
                                return;
                            }

                            $start = Carbon::parse($startRaw);
                            $end = Carbon::parse($value);

                            // Mismo día (mismo domingo) que el inicio
                            if (! $end->isSameDay($start)) {
                                $fail('La hora de fin debe ser el mismo día (domingo) que la hora de inicio.');

                                return;
                            }

                            if ($end->dayOfWeek !== Visit::ALLOWED_DAY_OF_WEEK) {
                                $fail('Las visitas solo se permiten los domingos.');

                                return;
                            }

                            if (! Visit::isWithinSundayWindow($end)) {
                                $fail('El horario permitido es de 14:00 a 17:00.');

                                return;
                            }

                            if ($end->lte($start)) {
                                $fail('La hora de fin debe ser posterior a la hora de inicio.');

                                return;
                            }
                        },
                    ]),
            ]);
    }
}
