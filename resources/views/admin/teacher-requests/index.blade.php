@extends('layouts.app')

@section('title', 'Solicitudes de profesor')

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/admin/teacher-requests.css') }}">
@endsection

@section('content')
    <div class="teacher-requests-page">
        <div class="teacher-requests-header">
            <div>
                <p class="teacher-requests-kicker">Administración</p>
                <h1>Solicitudes de profesor</h1>
                <p class="teacher-requests-subtitle">Revisa las solicitudes registradas en la base de datos y aprueba las que correspondan.</p>
            </div>

            <div class="teacher-requests-counter">
                <span class="teacher-requests-counter__label">Solicitudes Pendientes</span>
                <strong>{{ $requests->count() }}</strong>
            </div>
        </div>

        @forelse ($requests as $request)
            <article class="teacher-request-card">
                <div class="teacher-request-card__top">
                    <div>
                        <h2>{{ data_get($request, 'user.name', 'Usuario eliminado') }} {{ data_get($request, 'user.surname', '') }}</h2>
                        <p>@<span>{{ data_get($request, 'user.nickname', 'sin-nickname') }}</span> · {{ data_get($request, 'user.email', 'sin-email') }}</p>
                    </div>

                    <div class="teacher-request-card__meta">
                        <span class="teacher-request-meta__label">Creada</span>
                        <strong>{{ optional($request->created_at)->format('d/m/Y H:i') ?? 'Sin fecha' }}</strong>
                    </div>
                </div>

                <div class="teacher-request-grid">
                    <div class="teacher-request-field">
                        <span>Institución</span>
                        <strong>{{ $request->institution_name ?? 'No disponible' }}</strong>
                    </div>
                    <div class="teacher-request-field">
                        <span>Email institucional</span>
                        <strong>{{ $request->institutional_email ?? $request->institution_email ?? 'No disponible' }}</strong>
                    </div>
                    <div class="teacher-request-field teacher-request-field--full">
                        <span>Dirección</span>
                        <strong>{{ $request->address ?? 'No disponible en esta BBDD' }}</strong>
                    </div>
                </div>

                <div class="teacher-request-actions">
                    <form method="POST" action="{{ route('admin.teacher-requests.approve', $request) }}">
                        @csrf
                        <button type="submit" class="teacher-request-button">Aceptar solicitud</button>
                    </form>
                </div>
            </article>
        @empty
            <div class="teacher-requests-empty">
                <h2>No hay solicitudes visibles</h2>
                <p>Si tienes registros en la base de datos y no aparecen, revisa el valor de <strong>status</strong>. Este panel muestra estados <strong>submitted</strong> y <strong>pending</strong>.</p>
            </div>
        @endforelse
    </div>
@endsection