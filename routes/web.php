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
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\Admin\IncidentController as AdminIncidentController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\NotificationController;

// Ruta de inicio
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('feed');
    }

    return view('welcome');
})->name('home');

Route::get('/sobre-klassify', [PageController::class, 'about'])->name('pages.about');
Route::get('/normas-comunidad', [PageController::class, 'community'])->name('pages.community');
Route::get('/privacidad', [PageController::class, 'privacy'])->name('pages.privacy');

Route::get('/sitemap.xml', function () {
    $publicUrls = [
        route('home'),
        route('pages.about'),
        route('pages.community'),
        route('pages.privacy'),
    ];

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

    foreach ($publicUrls as $url) {
        $changefreq = $url === route('home') ? 'daily' : 'weekly';
        $priority = $url === route('home') ? '1.0' : '0.7';

        $xml .= "    <url>" . PHP_EOL;
        $xml .= '        <loc>' . e($url) . '</loc>' . PHP_EOL;
        $xml .= '        <changefreq>' . $changefreq . '</changefreq>' . PHP_EOL;
        $xml .= '        <priority>' . $priority . '</priority>' . PHP_EOL;
        $xml .= "    </url>" . PHP_EOL;
    }

    $xml .= '</urlset>' . PHP_EOL;

    return response($xml, 200)->header('Content-Type', 'application/xml');
})->name('sitemap');

// Rutas para usuarios invitados
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::get('/register/review', fn() => redirect()->route('register'));
    Route::post('/register/review', [RegisteredUserController::class, 'review'])->name('register.review');
    Route::get('/register/confirm', fn() => redirect()->route('register'));
    Route::post('/register/confirm', [RegisteredUserController::class, 'store'])->name('register.confirm');

    Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('forgot.password');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('forgot.password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
});

// Logout
Route::middleware('auth')->post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas para usuarios autenticados
Route::middleware('auth')->group(function () {
    // Feed
    Route::get('/feed', [FeedController::class, 'index'])->name('feed');
    Route::get('/feed/resources', [FeedController::class, 'resources'])->name('feed.resources');
    Route::get('/feed/search', [SearchController::class, 'feed'])->name('feed.search');

    // Notificaciones
    Route::get('/notificaciones', [NotificationController::class, 'index'])
        ->name('notifications.index');

    // Ayuda / Incidencias
    Route::get('/ayuda', [IncidentController::class, 'create'])->name('incidents.create');
    Route::post('/ayuda/incidencias', [IncidentController::class, 'store'])->name('incidents.store');

    // Perfil de usuario
    Route::get('/perfil', [ProfileController::class, 'me'])->name('profile.me');

    Route::get('/perfil/{user:nickname}/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/perfil/{user:nickname}', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::get('/perfil/{user:nickname}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/perfil/{user:nickname}/resources', [ProfileController::class, 'resources'])->name('profile.resources');
    Route::get('/perfil/{user:nickname}', [ProfileController::class, 'show'])->name('profile.show');

    // Rutas para cargar más recursos destacados en el feed
    Route::get('/feed/featured-resources/more', [FeedController::class, 'moreFeaturedResources'])
        ->name('feed.featured-resources.more');

    // Rutas para cargar más profesores sugeridos en el feed
    Route::get('/feed/suggested-teachers/more', [FeedController::class, 'moreSuggestedTeachers'])
        ->name('feed.suggested-teachers.more');

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
        Route::post('/{resource}/like', [LikeController::class, 'toggle'])->name('like.toggle');

        // Denuncias de recursos y comentarios
        Route::post('/{resource}/report', [ReportController::class, 'storeResource'])->name('report.store');
        Route::post('/comments/{comment}/report', [ReportController::class, 'storeComment'])->name('comments.report.store');

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

        // Gestión de denuncias
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::post('/reports/{report}/resolve', [AdminReportController::class, 'resolve'])->name('reports.resolve');

        // Gestión de incidencias
        Route::get('/incidencias', [AdminIncidentController::class, 'index'])->name('incidents.index');
        Route::post('/incidencias/{incident}/resolve', [AdminIncidentController::class, 'resolve'])->name('incidents.resolve');

        // Gestión de usuarios
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    });

// Rutas públicas para confirmación de solicitudes de profesor por institución
Route::get('/teacher-requests/confirm/{token}', [TeacherRequestController::class, 'confirmByInstitution'])
    ->name('teacher-requests.confirm');
