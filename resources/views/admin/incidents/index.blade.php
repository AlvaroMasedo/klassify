@extends('layouts.app')

@section('title', 'Incidencias')

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/admin/reports.css') }}">
@endsection

@section('content')
<main class="admin-reports-page">
    <section class="admin-reports-card">
        <header class="admin-reports-header">
            <div>
                <span class="admin-reports-kicker">Panel de administración</span>
                <h1>Incidencias</h1>
                <p>Revisa, filtra y cierra las incidencias enviadas desde Ayuda.</p>
            </div>

            <form method="GET" action="{{ route('admin.incidents.index') }}" class="admin-reports-filter-form">
                <label for="status" class="admin-reports-filter-label">Estado</label>

                <select id="status" name="status" class="admin-reports-filter" onchange="this.form.submit()">
                    <option value="open" @selected($status==='open' )>Abiertas</option>
                    <option value="resolved" @selected($status==='resolved' )>Resueltas</option>
                    <option value="all" @selected($status==='all' )>Todas</option>
                </select>
            </form>
        </header>

        @if (session('success'))
        <div class="admin-reports-success">
            {{ session('success') }}
        </div>
        @endif

        <div class="admin-reports-list">
            @forelse ($incidents as $incident)
            @php
            $userName = trim(($incident->user->name ?? '') . ' ' . ($incident->user->surname ?? ''));
            $userName = $userName !== '' ? $userName : ($incident->user->nickname ?? 'Usuario');
            $userNick = $incident->user?->nickname ? '@' . $incident->user->nickname : null;

            $typeLabels = [
            'technical' => 'Problema técnico',
            'user' => 'Problema de usuario',
            ];

            $typeLabel = $typeLabels[$incident->type] ?? 'Incidencia';
            @endphp

            <article class="admin-report-item {{ $incident->status === 'open' ? 'is-open' : 'is-resolved' }}">
                <div class="admin-report-content">
                    <div class="admin-report-top">
                        <span class="admin-report-status {{ $incident->status === 'open' ? 'is-open' : 'is-resolved' }}">
                            {{ $incident->status === 'open' ? 'Abierta' : 'Resuelta' }}
                        </span>

                        <span class="admin-report-type">
                            {{ $typeLabel }}
                        </span>

                        <span class="admin-report-date">
                            {{ $incident->created_at?->format('d/m/Y H:i') ?? 'Sin fecha' }}
                        </span>
                    </div>

                    <div class="admin-report-title-row">
                        <h2>{{ $incident->title }}</h2>
                    </div>

                    <div class="admin-report-grid">
                        <div class="admin-report-field">
                            <span>Usuario que informa</span>

                            <strong>
                                {{ $userName }}

                                @if ($userNick)
                                <small>{{ $userNick }}</small>
                                @endif
                            </strong>
                        </div>

                        <div class="admin-report-field">
                            <span>Tipo de incidencia</span>
                            <strong>{{ $typeLabel }}</strong>
                        </div>
                    </div>

                    <div class="admin-report-reason">
                        <span>Descripción indicada</span>
                        <p>{{ $incident->description }}</p>
                    </div>
                </div>

                <aside class="admin-report-actions">
                    @if ($incident->status === 'open')
                    <form method="POST" action="{{ route('admin.incidents.resolve', $incident) }}">
                        @csrf

                        <button type="submit" class="admin-report-resolve-btn">
                            Cerrar incidencia
                        </button>
                    </form>
                    @else
                    <span class="admin-report-resolved-label">
                        Cerrada
                    </span>
                    @endif
                </aside>
            </article>
            @empty
            <div class="admin-reports-empty">
                <strong>No hay incidencias con este filtro.</strong>
                <span>Cuando un usuario envíe una incidencia, aparecerá aquí.</span>
            </div>
            @endforelse
        </div>

        <div class="admin-reports-pagination">
            {{ $incidents->links() }}
        </div>
    </section>
</main>
@endsection