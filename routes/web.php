<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FeedController;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('feed');
    }

    return view('welcome');
})->name('home');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::get('/register/review', fn () => redirect()->route('register'));
    Route::post('/register/review', [RegisteredUserController::class, 'review'])->name('register.review');
    Route::get('/register/confirm', fn () => redirect()->route('register'));
    Route::post('/register/confirm', [RegisteredUserController::class, 'store'])->name('register.confirm');
});

Route::middleware('auth')->get('/feed', [FeedController::class, 'index'])->name('feed');

Route::get('/teacher/pending', function () {
    return view('auth.teacher-pending');
})->name('teacher.pending');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('forgot.password');

Route::post('/forgot-password', function (Illuminate\Http\Request $request) {
    $request->validate([
        'email' => ['required', 'email'],
    ]);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    $successMessage = 'Te hemos enviado por correo el enlace para restablecer tu contraseña.';

    $errorMessage = $status === Password::INVALID_USER
        ? 'No existe ninguna cuenta registrada con ese correo electrónico.'
        : 'No hemos podido enviar el enlace de recuperación. Inténtalo de nuevo en unos minutos.';

    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', $successMessage)
        : back()->withInput($request->only('email'))->withErrors([
            'email' => $errorMessage,
        ]);
})->name('forgot.password.email');

Route::get('/reset-password/{token}', function (string $token, Illuminate\Http\Request $request) {
    return view('auth.reset-password', [
        'token' => $token,
        'email' => $request->email,
    ]);
})->name('password.reset');

Route::post('/reset-password', function (Illuminate\Http\Request $request) {
    $request->validate([
        'token' => ['required'],
        'email' => ['required', 'email'],
        'password' => [
            'required',
            'confirmed',
            'min:8',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
        ],
    ], [
        'password.required' => 'La contraseña es obligatoria.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula, un número y un símbolo (@$!%*?&).',
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'El correo electrónico no es válido.',
        'token.required' => 'El token de recuperación es requerido.',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withInput($request->only('email'))->withErrors([
            'email' => __($status),
        ]);
})->name('password.update');

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class,'logout'])->name('logout');