<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    protected function getCredentialsFromFormData(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if ($user && ! $user->is_active) {
            throw ValidationException::withMessages([
                'data.email' => 'Tu cuenta está desactivada. Contacta al administrador.',
            ]);
        }

        return [
            'email'    => $data['email'],
            'password' => $data['password'],
        ];
    }
}