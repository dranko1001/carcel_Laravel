<?php

namespace App\Listeners;

use App\Models\LoginLog;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    /**
     * Requisito del proyecto: registrar ingresos de guardias al sistema.
     * Los administradores no se guardan aquí para no mezclar auditoría de guardias.
     */
    public function handle(Login $event): void
    {
        /** @var \App\Models\User $user */
        $user = $event->user;

        if ($user->role !== 'guard') {
            return;
        }

        LoginLog::create([
            'user_id' => $user->id,
            'login_at' => now(),
        ]);
    }
}