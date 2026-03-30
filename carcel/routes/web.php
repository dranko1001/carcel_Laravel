<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
use App\Http\Controllers\ReporteController;

Route::middleware(['auth'])->group(function () {
    Route::get('/reporte/excel', [ReporteController::class, 'exportarExcel'])->name('reporte.excel');
    Route::get('/reporte/pdf', [ReporteController::class, 'exportarPdf'])->name('reporte.pdf');
});