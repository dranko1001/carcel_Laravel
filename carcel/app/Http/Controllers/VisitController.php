<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VisitController extends Controller
{
    //
    public function create() {
    $prisoners = \App\Models\Prisoner::all();
    $visitors = \App\Models\Visitor::all();
    return view('visits.create', compact('prisoners', 'visitors'));
}
}
