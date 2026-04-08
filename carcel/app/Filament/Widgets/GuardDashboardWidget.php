<?php

namespace App\Filament\Widgets;

use App\Models\LoginLog;
use App\Models\Visit;
use Filament\Widgets\Widget;

class GuardDashboardWidget extends Widget
{
    protected string $view = 'filament.widgets.guard-dashboard-widget';

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $loginAt = LoginLog::where('user_id', auth()->id())
            ->latest('login_at')
            ->value('login_at');

        $loginTime = $loginAt ? \Illuminate\Support\Carbon::parse($loginAt)->format('H:i') : null;

        $pendingVisits = Visit::query()
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->with(['prisoner', 'visitor'])
            ->limit(5)
            ->get();

        return [
            'loginTime' => $loginTime,
            'pendingVisits' => $pendingVisits,
        ];
    }
}
