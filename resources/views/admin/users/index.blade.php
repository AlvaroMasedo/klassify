@extends('layouts.app')

@section('title', 'Usuarios')

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/admin/users.css') }}">
@endsection

@section('content')
<main class="admin-users-page">
    <section class="admin-users-card">
        <header class="admin-users-header">
            <div>
                <span class="admin-users-kicker">Panel de administración</span>
                <h1>Usuarios</h1>
                <p>Gestiona estudiantes, profesores y administradores registrados en Klassify.</p>
            </div>

            <form method="GET" action="{{ route('admin.users.index') }}" class="admin-users-filter-form">
                <label for="filter" class="admin-users-filter-label">Filtrar por</label>

                <select id="filter" name="filter" class="admin-users-filter" onchange="this.form.submit()">
                    <option value="students" @selected($filter === 'students')>Estudiantes</option>
                    <option value="teachers" @selected($filter === 'teachers')>Profesores</option>
                    <option value="admins" @selected($filter === 'admins')>Administradores</option>
                </select>
            </form>
        </header>

        @if (session('success'))
            <div class="admin-users-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="admin-users-warning">
                {{ session('warning') }}
            </div>
        @endif

        @if (session('error'))
            <div class="admin-users-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="admin-users-list">
            @forelse ($users as $listedUser)
                @php
                    $displayName = trim(((string) $listedUser->name) . ' ' . ((string) $listedUser->surname));
                    $displayName = $displayName !== '' ? $displayName : ($listedUser->nickname ?? 'Usuario');

                    $role = strtoupper((string) $listedUser->role);

                    $roleLabel = match ($role) {
                        'ADMIN' => 'Administrador',
                        'TEACHER' => 'Profesor',
                        default => 'Estudiante',
                    };

                    $teacherStatus = strtolower((string) ($listedUser->teacher_status ?? ''));
                    $teacherStatusLabel = $teacherStatus === 'pending' ? 'Pending' : 'Active';
                @endphp

                <article class="admin-user-item">
                    <div class="admin-user-main">
                        <div class="admin-user-avatar">
                            <x-user-avatar :user="$profileUser" alt="Avatar de {{ $displayName }}" />
                        </div>

                        <div class="admin-user-info">
                            <div class="admin-user-top">
                                <h2>{{ $displayName }}</h2>

                                <span class="admin-user-role {{ strtolower($roleLabel) }}">
                                    {{ $roleLabel }}
                                </span>

                                @if ($role === 'TEACHER')
                                    <span class="admin-user-teacher-status {{ $teacherStatus === 'pending' ? 'is-pending' : 'is-active' }}">
                                        {{ $teacherStatusLabel }}
                                    </span>
                                @endif
                            </div>

                            <div class="admin-user-meta">
                                <span>{{ '@' . ($listedUser->nickname ?? 'usuario') }}</span>
                                <span>{{ $listedUser->email }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="admin-user-actions">
                        <a href="{{ route('profile.show', ['user' => $listedUser->nickname]) }}" class="admin-user-profile-btn">
                            Ver perfil
                        </a>

                        <form
                            method="POST"
                            action="{{ route('admin.users.destroy', $listedUser) }}"
                            class="admin-user-delete-form"
                            onsubmit="return confirm('¿Seguro que quieres eliminar este usuario? Se eliminarán también sus recursos subidos, comentarios, likes, favoritos, seguimientos, denuncias e incidencias asociadas. Además se enviará un correo informativo al usuario. Esta acción no se puede deshacer.');">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="admin-user-delete-btn">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="admin-users-empty">
                    <strong>No hay usuarios con este filtro.</strong>
                    <span>Prueba con otro tipo de usuario.</span>
                </div>
            @endforelse
        </div>

        <div class="admin-users-pagination">
            {{ $users->links() }}
        </div>
    </section>
</main>
@endsection