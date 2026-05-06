@php
    $teacherDisplayName = trim(((string) $teacher->name) . ' ' . ((string) $teacher->surname)) ?: 'Usuario';
    $teacherUsername = '@' . ($teacher->nickname ?? 'usuario');
    $isFollowing = (bool) ($teacher->is_following ?? false);

    $teacherMeta = $teacher->institution?->name
        ? 'Centro: ' . $teacher->institution->name
        : ($teacher->specialization ? 'Curso: ' . $teacher->specialization : 'Profesor en Klassify');
@endphp

<div class="teacher-card">
    <div class="teacher-header">
        <a
            class="teacher-user-info teacher-profile-link"
            href="{{ route('profile.show', ['user' => $teacher->nickname]) }}">
            <div class="teacher-avatar">
                <x-user-avatar :user="$teacher" alt="Avatar de {{ $teacherDisplayName }}" />
            </div>

            <div class="teacher-user-details">
                <div class="teacher-name-container">
                    <span class="teacher-name">{{ $teacherDisplayName }}</span>

                    <span class="teacher-username">
                        {{ $teacherUsername }}
                        <x-verified-badge :user="$teacher" />
                    </span>
                </div>

                <p class="teacher-meta">{{ $teacherMeta }}</p>
            </div>
        </a>

        <button
            type="button"
            class="teacher-follow-btn {{ $isFollowing ? 'is-following' : '' }}"
            data-follow-toggle
            data-user-id="{{ $teacher->id }}"
            data-follow-url="{{ route('profile.follow.toggle', ['user' => $teacher->nickname]) }}"
            aria-pressed="{{ $isFollowing ? 'true' : 'false' }}">
            <span>{{ $isFollowing ? 'Siguiendo' : 'Seguir' }}</span>
        </button>
    </div>
</div>