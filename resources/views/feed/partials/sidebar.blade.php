<div class="k-recursosDestacados-card">
    <h2>Profesores Sugeridos</h2>

    @forelse (($suggestedTeachers ?? collect()) as $teacher)
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
                        <img src="{{ asset('assets/img/default-profile-img.png') }}" alt="Avatar de {{ $teacherDisplayName }}">
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
    @empty
        <p class="teacher-empty">No hay profesores sugeridos todavía.</p>
    @endforelse
</div>

<div class="sidebar-footer">
    <nav class="sidebar-footer-links">
        <a href="#">Sobre Klassify</a>
        <a href="#">Normas de la Comunidad</a>
        <a href="#">Privacidad</a>
        <a href="#">Ayuda</a>
        <a href="#">Contacto</a>
    </nav>

    <div class="sidebar-footer-brand">
        <img src="{{ asset('assets/img/k-logo.png') }}" alt="Logo de Klassify">
        <span>© {{ date('Y') }} Klassify</span>
    </div>
</div>