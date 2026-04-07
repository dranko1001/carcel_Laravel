<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Widgets\WidgetConfiguration;

/**
 * Reemplaza el dashboard por defecto: los guardias ven una cabecera propia del contexto carcelario;
 * los administradores conservan el aspecto estándar de Filament en esta página.
 */
class InstitutionalDashboard extends Dashboard
{
    /** Evita duplicar la ruta “/” al usar ->pages([...]) y discoverPages a la vez. */
    protected static bool $isDiscovered = false;

    public function content(Schema $schema): Schema
    {
        $prepend = [];
        if (auth()->user()?->role === 'guard') {
            $prepend[] = View::make('filament.pages.guard-dashboard-header');
        }

        return $schema
            ->components([
                ...$prepend,
                ...(method_exists($this, 'getFiltersForm') ? [$this->getFiltersFormContentComponent()] : []),
                $this->getWidgetsContentComponent(),
            ]);
    }

    /**
     * Quitamos el widget genérico “Filament” a los guardias para que el inicio se vea más serio y local al proyecto.
     *
     * @return array<class-string<\Filament\Widgets\Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        $widgets = parent::getWidgets();

        if (auth()->user()?->role !== 'guard') {
            return $widgets;
        }

        return collect($widgets)
            ->reject(function (string | WidgetConfiguration $widget): bool {
                $class = $widget instanceof WidgetConfiguration
                    ? $widget->widget
                    : $widget;

                return $class === FilamentInfoWidget::class;
            })
            ->values()
            ->all();
    }
}
