<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ResourcePreviewController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TeacherRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FollowController;

// Ruta de inicio
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('feed');
    }
    return view('welcome');
})->name('home');

// Rutas para usuarios invitados (no autenticados)
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    // Register
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::get('/register/review', fn() => redirect()->route('register'));
    Route::post('/register/review', [RegisteredUserController::class, 'review'])->name('register.review');
    Route::get('/register/confirm', fn() => redirect()->route('register'));
    Route::post('/register/confirm', [RegisteredUserController::class, 'store'])->name('register.confirm');

    // Password reset
    Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('forgot.password');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('forgot.password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
});

// Logout (disponible para autenticados)
Route::middleware('auth')->post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas para usuarios autenticados
Route::middleware('auth')->group(function () {
    // Feed
    Route::get('/feed', [FeedController::class, 'index'])->name('feed');
    Route::get('/feed/resources', [FeedController::class, 'resources'])->name('feed.resources');

    // Perfil de usuario
    Route::get('/perfil', [ProfileController::class, 'me'])->name('profile.me');
    Route::get('/perfil/{user:nickname}', [ProfileController::class, 'show'])->name('profile.show');

    // Seguir/dejar de seguir a un usuario
    Route::post('/perfil/{user:nickname}/follow', [FollowController::class, 'toggle'])
    ->name('profile.follow.toggle');

    // Recursos
    Route::prefix('resources')->name('resources.')->group(function () {
        Route::get('/create', [ResourceController::class, 'entry'])->name('create');
        Route::get('/{resource}/preview', [ResourcePreviewController::class, 'show'])->name('preview');
        Route::get('/{resource}/edit', [ResourceController::class, 'edit'])->name('edit');
        Route::put('/{resource}', [ResourceController::class, 'update'])->name('update');
        Route::delete('/{resource}', [ResourceController::class, 'destroy'])->name('destroy');
        Route::post('/{resource}/comments', [CommentController::class, 'store'])->name('comments.store');
        Route::delete('/{resource}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
        Route::post('/{resource}/favorite', [FavoriteController::class, 'toggle'])->name('favorite.toggle');
        Route::get('/{resource}', [ResourceController::class, 'show'])->name('show');
    });

    // Pendiente de profesor
    Route::get('/teacher/pending', function () {
        return view('auth.teacher-pending');
    })->name('teacher.pending');
});

// Rutas para profesores autenticados y verificados
Route::middleware(['auth', 'teacher', 'teacher.verified'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        Route::prefix('resources')->name('resources.')->group(function () {
            Route::get('/create', [ResourceController::class, 'create'])->name('create');
            Route::post('', [ResourceController::class, 'store'])->name('store');
        });
    });

// Rutas para administradores
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Recursos
        Route::prefix('resources')->name('resources.')->group(function () {
            Route::get('/create', [ResourceController::class, 'create'])->name('create');
            Route::post('', [ResourceController::class, 'store'])->name('store');
        });

        // Gestión de solicitudes de profesores
        Route::prefix('teacher-requests')->name('teacher-requests.')->group(function () {
            Route::get('', [TeacherRequestController::class, 'index'])->name('index');
            Route::post('{teacherRequest}/approve', [TeacherRequestController::class, 'approve'])->name('approve');
            Route::post('{teacherRequest}/reject', [TeacherRequestController::class, 'reject'])->name('reject');
        });
    });

// Rutas públicas para confirmación de solicitudes de profesor por institución
Route::get('/teacher-requests/confirm/{token}', [TeacherRequestController::class, 'confirmByInstitution'])
    ->name('teacher-requests.confirm');
