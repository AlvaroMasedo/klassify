<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(ForgotPasswordRequest $request): RedirectResponse
    {
        $status = Password::sendResetLink($request->only('email'));

        $successMessage = 'Te hemos enviado por correo el enlace para restablecer tu contraseña.';

        $errorMessage = $status === Password::INVALID_USER
            ? 'No existe ninguna cuenta registrada con ese correo electrónico.'
            : 'No hemos podido enviar el enlace de recuperación. Inténtalo de nuevo en unos minutos.';

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', $successMessage)
            : back()->withInput($request->only('email'))->withErrors([
                'email' => $errorMessage,
            ]);
    }

    public function edit(string $token, Request $request): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function update(ResetPasswordRequest $request): RedirectResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        $successMessage = 'Tu contraseña se ha restablecido correctamente. Ya puedes iniciar sesión.';

        $errorMessage = $status === Password::INVALID_TOKEN
            ? 'El enlace de recuperación no es válido o ha expirado.'
            : ($status === Password::INVALID_USER
                ? 'No existe ninguna cuenta registrada con ese correo electrónico.'
                : 'No hemos podido restablecer la contraseña. Inténtalo de nuevo.');

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', $successMessage)
            : back()->withInput($request->only('email'))->withErrors([
                'email' => $errorMessage,
            ]);
    }
}
