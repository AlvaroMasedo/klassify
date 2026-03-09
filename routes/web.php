<?php

use App\Http\Controllers\FeedController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    // Si hay usuario logueado -> vamos al feed
    if (Auth::check()) {
        return redirect()->route('feed');
    }

    // Temporal: mientras no exista welcome, enviamos también al feed
    return redirect()->route('feed');
})->name('home');

Route::get('/feed', [FeedController::class, 'index'])->name('feed'); 