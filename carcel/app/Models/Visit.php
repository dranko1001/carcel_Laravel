<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Visit extends Model
{
    use HasFactory;

    /**
     * Regla del SENA: visitas solo domingo, entre 14:00 y 17:00 (inclusive en los extremos del tramo permitido).
     * Carbon::SUNDAY = 0.
     */
    public const ALLOWED_DAY_OF_WEEK = Carbon::SUNDAY;

    /** Minutos desde medianoche: 14:00 */
    public const WINDOW_START_MINUTES = 14 * 60;

    /** Minutos desde medianoche: 17:00 (última hora permitida para inicio o fin) */
    public const WINDOW_END_MINUTES = 17 * 60;

    protected $fillable = [
        'prisoner_id',
        'visitor_id',
        'relationship',
        'user_id',
        'start_time',
        'end_time',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function prisoner()
    {
        return $this->belongsTo(Prisoner::class);
    }

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    /**
     * Convierte una fecha/hora a minutos del día (0–1439) para comparar con la ventana 14:00–17:00.
     */
    public static function minutesOfDay(Carbon $dateTime): int
    {
        return ((int) $dateTime->format('H')) * 60 + (int) $dateTime->format('i');
    }

    /**
     * Indica si la marca temporal cae en domingo y entre 14:00 y 17:00 inclusive.
     */
    public static function isWithinSundayWindow(Carbon $dateTime): bool
    {
        if ($dateTime->dayOfWeek !== self::ALLOWED_DAY_OF_WEEK) {
            return false;
        }

        $m = self::minutesOfDay($dateTime);

        return $m >= self::WINDOW_START_MINUTES && $m <= self::WINDOW_END_MINUTES;
    }

    /**
     * Estados que “ocupan” agenda: si está cancelada, el mismo prisionero puede volver a agendar ese tramo.
     *
     * @return array<int, string>
     */
    public static function blockingStatuses(): array
    {
        return ['pendiente', 'completada'];
    }

    /**
     * Dos intervalos [inicio, fin] se solapan si hay cualquier minuto en común (límite exclusivo en el toque:
     * una visita 14:00–14:30 y otra 14:30–15:00 NO chocan).
     */
    public static function rangesOverlap(Carbon $startA, Carbon $endA, Carbon $startB, Carbon $endB): bool
    {
        return $startA->lt($endB) && $endA->gt($startB);
    }

    /**
     * Comprueba si ya existe otra visita “activa” del mismo prisionero que choque en horario con el tramo dado.
     *
     * @param  int|null  $exceptVisitId  En edición, ignoramos el registro actual.
     */
    public static function prisonerHasScheduleConflict(
        int $prisonerId,
        Carbon $start,
        Carbon $end,
        ?int $exceptVisitId = null
    ): bool {
        if ($end->lte($start)) {
            return false;
        }

        return static::query()
            ->where('prisoner_id', $prisonerId)
            ->whereIn('status', self::blockingStatuses())
            ->when($exceptVisitId, fn ($q) => $q->where('id', '!=', $exceptVisitId))
            ->where(function ($q) use ($start, $end) {
                // Solape estándar de intervalos en SQL
                $q->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            })
            ->exists();
    }
}