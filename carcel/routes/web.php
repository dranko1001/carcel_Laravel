<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrisonerController;
use App\Http\Controllers\VisitorController;

Route::get('/', function () {
    return redirect('/admin');
});
use App\Http\Controllers\ReporteController;

Route::middleware(['auth'])->group(function () {
    Route::get('/reporte/excel', [ReporteController::class, 'exportarExcel'])->name('reporte.excel');
    Route::get('/reporte/pdf', [ReporteController::class, 'exportarPdf'])->name('reporte.pdf');
});