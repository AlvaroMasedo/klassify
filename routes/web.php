<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    // Si hay usuario logueado -> vamos al feed
    if (Auth::check()) {
        return redirect()->route('feed');
    }

    // Si NO hay usuario logueado -> mostramos welcome
    return view('feed.index'); //CAMBIAR POR WELCOME 
})->name('home');

Route::get('/feed', function () {
    // Si NO hay usuario logueado -> lo mandamos a home
    if (!Auth::check()) {
        return redirect()->route('home');
    }

    return view('feed.index');
})->name('feed');