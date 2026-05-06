@php
    $displayName = trim(((string) $user->name) . ' ' . ((string) $user->surname)) ?: 'Usuario';
    $username = '@' . ($user->nickname ?? 'usuario');
    $isFollowing = (bool) ($user->is_following ?? false);

    $meta = $user->institution?->name
        ? 'Centro: ' . $user->institution->name
        : ($user->specialization ? 'Curso: ' . $user->specialization : 'Usuario de Klassify');
@endphp

<div class="search-user-card">
    <a class="search-user-link" href="{{ route('profile.show', ['user' => $user->nickname]) }}">
        <div class="search-user-avatar">
            <x-user-avatar :user="$resource->user" alt="Avatar de {{ $resourceUserName }}" />
        </div>

        <div class="search-user-info">
            <strong>{{ $displayName }}</strong>

            <span>
                {{ $username }}
                <x-verified-badge :user="$user" />
            </span>

            <small>{{ $meta }}</small>
        </div>
    </a>

    <button
        type="button"
        class="teacher-follow-btn {{ $isFollowing ? 'is-following' : '' }}"
        data-follow-toggle
        data-user-id="{{ $user->id }}"
        data-follow-url="{{ route('profile.follow.toggle', ['user' => $user->nickname]) }}"
        aria-pressed="{{ $isFollowing ? 'true' : 'false' }}">
        <span>{{ $isFollowing ? 'Siguiendo' : 'Seguir' }}</span>
    </button>
</div>