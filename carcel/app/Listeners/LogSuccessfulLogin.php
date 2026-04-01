<?php
namespace App\Listeners;

use App\Models\LoginLog;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    public function handle(Login $event): void
    {
        /** @var \App\Models\User $user */
        $user = $event->user;

        LoginLog::create([
            'user_id' => $user->id,
            'login_at' => now(),
        ]);
    }
}