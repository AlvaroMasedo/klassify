<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\ResourceController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//Si el usuario ya ha iniciado sesión, redirige a la página de feed
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('feed');
    }
    // Si no ha iniciado sesión, muestra la página de bienvenida
    return view('welcome');
})->name('home');

// Rutas para usuarios invitados (no autenticados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::get('/register/review', fn () => redirect()->route('register'));
    Route::post('/register/review', [RegisteredUserController::class, 'review'])->name('register.review');
    Route::get('/register/confirm', fn () => redirect()->route('register'));
    Route::post('/register/confirm', [RegisteredUserController::class, 'store'])->name('register.confirm');

    Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('forgot.password');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('forgot.password.email');

    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
});

// Rutas para usuarios autenticados
Route::middleware('auth')->get('/feed', [FeedController::class, 'index'])->name('feed');
Route::middleware('auth')->get('/resources/create', [ResourceController::class, 'entry'])->name('resources.create');

// Ruta para la página de "pendiente de aprobación" de profesores
Route::get('/teacher/pending', function () {
    return view('auth.teacher-pending');
})->name('teacher.pending');

// Rutas de autenticación de sesión iniciada y cierre de sesión
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class,'logout'])->name('logout');

// Rutas para profesores autenticados y verificados
Route::middleware(['auth', 'teacher', 'teacher.verified'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        Route::get('/resources/create', [ResourceController::class, 'create'])->name('resources.create');
        Route::post('/resources', [ResourceController::class, 'store'])->name('resources.store');
    });

// Rutas para administradores
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/resources/create', [ResourceController::class, 'create'])->name('resources.create');
        Route::post('/resources', [ResourceController::class, 'store'])->name('resources.store');
});