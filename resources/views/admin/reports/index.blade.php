@extends('layouts.app')

@section('title', 'Denuncias')

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/admin/reports.css') }}">
@endsection

@section('content')
<main class="admin-reports-page">
    <section class="admin-reports-card">
        <header class="admin-reports-header">
            <div>
                <span class="admin-reports-kicker">Panel de administración</span>
                <h1>Denuncias</h1>
                <p>Revisa, filtra y cierra los reportes enviados por los usuarios.</p>
            </div>

            <form method="GET" action="{{ route('admin.reports.index') }}" class="admin-reports-filter-form">
                <label for="status" class="admin-reports-filter-label">Estado</label>
                <select id="status" name="status" class="admin-reports-filter" onchange="this.form.submit()">
                    <option value="open" @selected($status === 'open')>Abiertas</option>
                    <option value="resolved" @selected($status === 'resolved')>Resueltas</option>
                    <option value="all" @selected($status === 'all')>Todas</option>
                </select>
            </form>
        </header>

        @if (session('success'))
            <div class="admin-reports-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="admin-reports-list">
            @forelse ($reports as $report)
                @php
                    $reporterName = trim(($report->reporter->name ?? '') . ' ' . ($report->reporter->surname ?? ''));
                    $reporterName = $reporterName !== '' ? $reporterName : ($report->reporter->nickname ?? 'Usuario');

                    $reporterNick = $report->reporter?->nickname ? '@' . $report->reporter->nickname : null;
                    $isCommentReport = !empty($report->comment_id);

                    $commentText = null;

                    if ($report->comment) {
                        $commentText = $report->comment->comment
                            ?? $report->comment->content
                            ?? $report->comment->body
                            ?? $report->comment->message
                            ?? null;
                    }

                    $resourceTitle = $report->resource?->title ?? 'Recurso eliminado';
                @endphp

                <article class="admin-report-item {{ $report->status === 'open' ? 'is-open' : 'is-resolved' }}">
                    <div class="admin-report-content">
                        <div class="admin-report-top">
                            <span class="admin-report-status {{ $report->status === 'open' ? 'is-open' : 'is-resolved' }}">
                                {{ $report->status === 'open' ? 'Abierta' : 'Resuelta' }}
                            </span>

                            <span class="admin-report-type">
                                {{ $isCommentReport ? 'Comentario' : 'Recurso' }}
                            </span>

                            <span class="admin-report-date">
                                {{ $report->created_at?->format('d/m/Y H:i') ?? 'Sin fecha' }}
                            </span>
                        </div>

                        <div class="admin-report-title-row">
                            <h2>{{ $isCommentReport ? 'Denuncia de comentario' : 'Denuncia de recurso' }}</h2>
                        </div>

                        <div class="admin-report-grid">
                            <div class="admin-report-field">
                                <span>Usuario que denuncia</span>

                                <strong>
                                    {{ $reporterName }}

                                    @if ($reporterNick)
                                        <small>{{ $reporterNick }}</small>
                                    @endif
                                </strong>
                            </div>

                            <div class="admin-report-field">
                                <span>Recurso afectado</span>

                                @if ($report->resource)
                                    <a href="{{ route('resources.show', $report->resource) }}">
                                        {{ $resourceTitle }}
                                    </a>
                                @else
                                    <strong>{{ $resourceTitle }}</strong>
                                @endif
                            </div>
                        </div>

                        @if ($isCommentReport)
                            <div class="admin-report-comment">
                                <span>Texto del comentario</span>
                                <p>{{ $commentText ?: 'Comentario eliminado o sin contenido disponible.' }}</p>
                            </div>
                        @endif

                        <div class="admin-report-reason">
                            <span>Motivo indicado</span>
                            <p>{{ $report->reason }}</p>
                        </div>
                    </div>

                    <aside class="admin-report-actions">
                        @if ($report->status === 'open')
                            <form method="POST" action="{{ route('admin.reports.resolve', $report) }}">
                                @csrf

                                <button type="submit" class="admin-report-resolve-btn">
                                    Cerrar denuncia
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
                    <strong>No hay denuncias con este filtro.</strong>
                    <span>Cuando un usuario reporte contenido, aparecerá aquí.</span>
                </div>
            @endforelse
        </div>

        <div class="admin-reports-pagination">
            {{ $reports->links() }}
        </div>
    </section>
</main>
@endsection