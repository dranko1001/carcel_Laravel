<?php
namespace App\Exports;

use App\Models\Visit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VisitasExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $fecha_inicio;
    protected $fecha_fin;

    public function __construct($fecha_inicio, $fecha_fin)
    {
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
    }

    public function collection()
    {
        return Visit::with(['prisoner', 'visitor', 'officer'])
            ->whereBetween('start_time', [
                $this->fecha_inicio,
                $this->fecha_fin . ' 23:59:59'
            ])
            ->orderBy('start_time', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Prisionero',
            'Visitante',
            'Relación',
            'Guardia',
            'Inicio',
            'Fin',
        ];
    }

    public function map($visit): array
    {
        return [
            $visit->id,
            $visit->prisoner->full_name ?? '-',
            $visit->visitor->full_name ?? '-',
            $visit->relationship,
            $visit->officer->name ?? '-',
            \Carbon\Carbon::parse($visit->start_time)->format('d/m/Y H:i'),
            \Carbon\Carbon::parse($visit->end_time)->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}