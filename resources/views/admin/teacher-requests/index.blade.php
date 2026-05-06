@extends('layouts.app')

@section('title', 'Solicitudes de profesor')

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/admin/teacher-requests.css') }}">
@endsection


@section('content')
    <section class="teacher-requests-page">
        <header class="teacher-requests-header">
            <div>
                <p class="teacher-requests-kicker">Panel Admin</p>
                <h1>Solicitudes de profesor</h1>
                <p class="teacher-requests-subtitle">Revisa solicitudes pendientes y gestiona su aprobación o rechazo.</p>
            </div>

            <div class="teacher-requests-counter">
                <span class="teacher-requests-counter__label">Total de solicitudes</span>
                <strong>{{ $teacherRequests->count() }}</strong>
            </div>
        </header>

        <form class="teacher-requests-filter" method="GET" action="{{ route('admin.teacher-requests.index') }}">
            <label for="status" class="teacher-requests-filter__label">Filtrar por estado</label>
            <select id="status" name="status" class="teacher-requests-filter__select">
                <option value="">Todas</option>
                <option value="SUBMITTED" @selected($selectedStatus === 'SUBMITTED')>Submitted</option>
                <option value="APPROVED" @selected($selectedStatus === 'APPROVED')>Approved</option>
                <option value="REJECTED" @selected($selectedStatus === 'REJECTED')>Rejected</option>
            </select>

            <button type="submit" class="teacher-requests-filter__button">Filtrar</button>
        </form>

        @forelse ($teacherRequests as $teacherRequest)
            @php
                $status = strtolower((string) $teacherRequest->status);
            @endphp
            <article class="k-card teacher-request-card">
                <div class="teacher-request-card__top">
                    <div>
                        <span class="teacher-request-badge teacher-request-badge--{{ $status }}">{{ $teacherRequest->status }}</span>
                        <h2>{{ $teacherRequest->user->name }} {{ $teacherRequest->user->surname }}</h2>
                        <p>{{ $teacherRequest->user->email }}</p>
                    </div>
                </div>

                <div class="teacher-request-grid">
                    <p class="teacher-request-field">
                        <span>Institución</span>
                        <strong>{{ $teacherRequest->institution_name }}</strong>
                    </p>
                    <p class="teacher-request-field">
                        <span>Email institución</span>
                        <strong>{{ $teacherRequest->institution_email }}</strong>
                    </p>
                    <p class="teacher-request-field teacher-request-field--full">
                        <span>Dirección</span>
                        <strong>{{ $teacherRequest->address }}</strong>
                    </p>
                </div>

                @if ($teacherRequest->status === 'SUBMITTED')
                    <div class="teacher-request-actions">
                        <form method="POST" action="{{ route('admin.teacher-requests.approve', $teacherRequest) }}">
                            @csrf
                            <button type="submit" class="teacher-request-button">Aprobar</button>
                        </form>

                        <form method="POST" action="{{ route('admin.teacher-requests.reject', $teacherRequest) }}">
                            @csrf
                            <button type="submit" class="teacher-request-button teacher-request-button--reject">Rechazar</button>
                        </form>
                    </div>
                @endif
            </article>
        @empty
            <section class="teacher-requests-empty">
                <h2>No hay solicitudes para este filtro</h2>
                <p>Prueba a cambiar el estado en el filtro para ver otras solicitudes.</p>
            </section>
        @endforelse
    </section>
@endsection