<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FeedController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class,'logout'])->name('logout');