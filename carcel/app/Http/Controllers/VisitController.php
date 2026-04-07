<?php

namespace App\Http\Controllers;

/**
 * Las visitas se gestionan en el panel Filament (/admin) y solo los guardias tienen acceso (VisitResource::canAccess).
 * Este controlador quedó como apoyo por si en el futuro agregan una vista web; la lógica de domingo 14:00–17:00
 * y de solapes está en App\Models\Visit y en los formularios/páginas de Filament.
 */
class VisitController extends Controller
{
    public function create()
    {
        $prisoners = \App\Models\Prisoner::all();
        $visitors = \App\Models\Visitor::all();

        return view('visits.create', compact('prisoners', 'visitors'));
    }
}
