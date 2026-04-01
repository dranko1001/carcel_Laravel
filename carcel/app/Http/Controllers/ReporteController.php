<?php
namespace App\Http\Controllers;

use App\Exports\VisitasExport;
use App\Models\Visit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReporteController extends Controller
{
    public function exportarExcel(Request $request)
    {
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;

        $nombre = 'visitas_' . $fecha_inicio . '_' . $fecha_fin . '.xlsx';

        return Excel::download(
            new VisitasExport($fecha_inicio, $fecha_fin),
            $nombre
        );
    }

    public function exportarPdf(Request $request)
    {
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;

        $visitas = Visit::with(['prisoner', 'visitor', 'officer'])
            ->whereBetween('start_time', [
                $fecha_inicio,
                $fecha_fin . ' 23:59:59'
            ])
            ->orderBy('start_time', 'desc')
            ->get();

        $pdf = Pdf::loadView('reportes.visitas_pdf', [
            'visitas' => $visitas,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('visitas.pdf');
    }
    
}