@extends('layouts.app')

@section('title', 'Notificaciones | Klassify')

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/notifications.css') }}">
@endsection

@section('content')
<main class="notifications-page">
    <section class="notifications-card">
        <header class="notifications-header">
            <div>
                <span>Actividad</span>
                <h1>Notificaciones</h1>
                <p>Estas son las últimas interacciones con tus publicaciones.</p>
            </div>

            <a href="{{ route('feed') }}" class="notifications-back-btn">Volver al feed</a>
        </header>

        <div class="notifications-list">
            @forelse ($notifications as $notification)
                @php
                    $actor = $notification->actor;
                    $actorName = trim(((string) ($actor->name ?? '')) . ' ' . ((string) ($actor->surname ?? '')));
                    $actorName = $actorName !== '' ? $actorName : ($actor->nickname ?? 'Usuario');

                    $message = $notification->type === 'like'
                        ? 'le ha dado like a una publicación.'
                        : 'ha comentado en una publicación.';
                @endphp

                @if ($notification->resource)
                    <a
                        href="{{ route('resources.show', $notification->resource) }}"
                        class="notification-item {{ $notification->read_at ? '' : 'is-unread' }}">
                        <div class="notification-avatar">
                            <x-user-avatar :user="$actor" alt="Avatar de {{ $actorName }}" />
                        </div>

                        <div class="notification-content">
                            <p>
                                <strong>{{ $actorName }}</strong>
                                {{ $message }}
                            </p>

                            <small>
                                {{ $notification->created_at?->locale('es')->diffForHumans() ?? 'Hace poco' }}
                            </small>
                        </div>

                        <div class="notification-type {{ $notification->type }}">
                            @if ($notification->type === 'like')
                                <svg viewBox="0 -960 960 960" aria-hidden="true">
                                    <path d="M480-120l-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Z" />
                                </svg>
                            @else
                                <svg viewBox="0 -960 960 960" aria-hidden="true">
                                    <path d="M80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Z" />
                                </svg>
                            @endif
                        </div>
                    </a>
                @endif
            @empty
                <div class="notifications-empty">
                    <h2>No tienes notificaciones todavía</h2>
                    <p>Cuando alguien dé like o comente una publicación tuya, aparecerá aquí.</p>
                </div>
            @endforelse
        </div>

        <div class="notifications-pagination">
            {{ $notifications->links() }}
        </div>
    </section>
</main>
@endsection