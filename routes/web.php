<?php

use App\Http\Controllers\FeedController;
use App\Models\Course;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/', function () {
    // Si hay usuario logueado -> vamos al feed
    if (Auth::check()) {
        return redirect()->route('feed');
    }

    // Si NO hay usuario logueado -> mostramos welcome
    return view('welcome');
})->name('home');

Route::get('/feed', [FeedController::class, 'index'])
    ->middleware('auth')
    ->name('feed');

Route::get('/register', function () {
    // Passem els cursos sense noms duplicats per omplir el select del formulari
    $courses = Course::orderBy('name')->get()->unique('name')->values();
    return view('auth.register', compact('courses'));
})->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register/review', [RegisteredUserController::class, 'review'])->name('register.review');
    Route::post('/register/confirm', [RegisteredUserController::class, 'store'])->name('register.confirm');
});

Route::middleware('auth')->get('/feed', [FeedController::class, 'index'])->name('feed');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');