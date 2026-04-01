<?php
namespace App\Filament\Pages;

use App\Models\Visit;
use Filament\Pages\Page;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class ReporteDashboard extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.reporte-dashboard';
    protected static ?string $navigationLabel = 'Reportes';
    protected static ?string $title = 'Reportes de Visitas';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentChartBar;
    protected static ?int $navigationSort = 5;

    public ?string $fecha_inicio = null;
    public ?string $fecha_fin = null;

    public static function canAccess(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('fecha_inicio')
                    ->label('Fecha Inicio')
                    ->required(),
                DatePicker::make('fecha_fin')
                    ->label('Fecha Fin')
                    ->required(),
            ]);
    }

    public function getVisitas()
    {
        if (!$this->fecha_inicio || !$this->fecha_fin) {
            return collect();
        }

        return Visit::with(['prisoner', 'visitor', 'officer'])
            ->whereBetween('start_time', [
                $this->fecha_inicio,
                $this->fecha_fin . ' 23:59:59'
            ])
            ->orderBy('start_time', 'desc')
            ->get();
    }

    public function exportarExcel()
    {
        if (!$this->fecha_inicio || !$this->fecha_fin) {
            \Filament\Notifications\Notification::make()
                ->title('Selecciona un rango de fechas')
                ->warning()
                ->send();
            return;
        }

        $url = route('reporte.excel', [
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
        ]);

        $this->js("window.open('{$url}', '_blank')");
    }

    public function exportarPdf()
    {
        if (!$this->fecha_inicio || !$this->fecha_fin) {
            \Filament\Notifications\Notification::make()
                ->title('Selecciona un rango de fechas')
                ->warning()
                ->send();
            return;
        }

        $url = route('reporte.pdf', [
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
        ]);

        $this->js("window.open('{$url}', '_blank')");
    }
}