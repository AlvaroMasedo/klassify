@extends('layouts.app')

@section('title', $profileUser->name . ' | Klassify')
@section('page', 'profile')

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/profile.css') }}">
@endsection

@section('content')
@php
$fullName = trim(((string) ($profileUser->name ?? '')) . ' ' . ((string) ($profileUser->surname ?? '')));
$displayName = $fullName !== '' ? $fullName : 'Usuario';
$nickname = (string) ($profileUser->nickname ?? 'usuario');

$role = strtoupper((string) ($profileUser->role ?? ''));
$isVerifiedTeacher = $role === 'TEACHER' && in_array(strtoupper((string) ($profileUser->teacher_status ?? '')), ['VERIFIED', 'ACTIVE'], true);

$profileSubtitle = $role === 'ADMIN'
? 'Administrador'
: ($isVerifiedTeacher ? 'Profesor Verificado' : ucfirst(strtolower($role ?: 'Usuario')));

$profileDescription = trim((string) ($profileUser->description ?? ''));
$specialization = trim((string) ($profileUser->specialization ?? ''));
$institutionName = trim((string) data_get($profileUser, 'institution.name', ''));

$institutionLocation = trim((string) (
data_get($profileUser, 'institution.location')
?? data_get($profileUser, 'institution.city')
?? data_get($profileUser, 'location')
?? data_get($profileUser, 'city')
?? ''
));

$hasAboutInfo = $profileDescription !== ''
|| $specialization !== ''
|| $institutionName !== ''
|| $institutionLocation !== '';

$isPrivateBlocked = (bool) ($isPrivateBlocked ?? false);
$showSocialInfo = (bool) ($showSocialInfo ?? false);
$canShowFollowButton = (bool) ($canShowFollowButton ?? false);
@endphp

<div class="profile-page">
    <div class="profile-shell">
        <section class="profile-card">
            <div class="profile-layout {{ $isPrivateBlocked ? 'profile-layout--private' : '' }}">
                <div class="profile-main">
                    <header class="profile-hero">
                        <div class="profile-avatar">
                            <x-user-avatar :user="$profileUser" alt="Avatar de {{ $displayName }}" />
                        </div>

                        <div class="profile-info">
                            <div class="profile-name-row">
                                <div>
                                    <h1>{{ $displayName }}</h1>

                                    <p class="profile-nickname">
                                        {{ '@' . $nickname }}
                                        <x-verified-badge :user="$profileUser" />
                                    </p>
                                </div>

                                <div class="profile-actions">
                                    @if ($showSocialInfo)
                                    <span
                                        class="profile-followers"
                                        data-followers-count-for="{{ $profileUser->id }}">
                                        {{ $followersCount }} {{ $followersCount === 1 ? 'Seguidor' : 'Seguidores' }}
                                    </span>

                                    @if ($isOwner)
                                    <a
                                        href="{{ route('profile.edit', ['user' => $profileUser->nickname]) }}"
                                        class="profile-edit-btn">
                                        Editar perfil
                                    </a>
                                    @elseif ($canShowFollowButton)
                                    <button
                                        type="button"
                                        class="profile-follow-btn {{ $isFollowing ? 'is-following' : '' }}"
                                        data-follow-toggle
                                        data-user-id="{{ $profileUser->id }}"
                                        data-follow-url="{{ route('profile.follow.toggle', ['user' => $profileUser->nickname]) }}"
                                        aria-pressed="{{ $isFollowing ? 'true' : 'false' }}">
                                        {{ $isFollowing ? 'Siguiendo' : 'Seguir' }}
                                    </button>
                                    @endif
                                    @endif
                                </div>
                            </div>

                            <div class="profile-meta">
                                <span>{{ $profileSubtitle }}</span>
                            </div>

                            <p class="profile-location">
                                {{ $institutionName !== '' ? $institutionName : 'Centro no indicado' }}
                            </p>

                            @if ($profileDescription !== '' && !$isPrivateBlocked)
                            <p class="profile-bio-mobile">{{ $profileDescription }}</p>
                            @endif
                        </div>
                    </header>

                    <nav class="profile-tabs" aria-label="Secciones del perfil">
                        <a
                            href="{{ route('profile.show', ['user' => $profileUser->nickname]) }}"
                            class="profile-tab {{ ($activeTab ?? 'resources') === 'resources' ? 'is-active' : '' }}">
                            Recursos
                        </a>

                        @if ($isOwner)
                        <a
                            href="{{ route('profile.show', ['user' => $profileUser->nickname, 'tab' => 'favorites']) }}"
                            class="profile-tab {{ ($activeTab ?? 'resources') === 'favorites' ? 'is-active' : '' }}">
                            Favoritos
                        </a>
                        @endif

                        <a href="#" class="profile-tab">Calendario</a>
                    </nav>

                    @if (!($isPrivateBlocked ?? false))
                    <div
                        class="profile-filters"
                        data-profile-filters
                        data-filter-url="{{ route('profile.resources', ['user' => $profileUser->nickname]) }}"
                        data-active-tab="{{ $activeTab ?? 'resources' }}">
                        <div class="profile-filter-group">
                            <select name="course_id" class="profile-select" data-profile-course>
                                <option value="">Curso</option>

                                @foreach ($courses as $course)
                                <option
                                    value="{{ $course->id }}"
                                    @selected((int) $selectedCourseId===(int) $course->id)>
                                    {{ $course->name }}
                                </option>
                                @endforeach
                            </select>

                            <select
                                name="subject_id"
                                class="profile-select"
                                data-profile-subject
                                {{ empty($selectedCourseId) ? 'disabled' : '' }}>
                                <option value="">Asignatura</option>

                                @foreach ($subjects as $subject)
                                <option
                                    value="{{ $subject->id }}"
                                    @selected((int) $selectedSubjectId===(int) $subject->id)>
                                    {{ $subject->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="profile-type-filters">
                            @php
                            $types = [
                            'image' => 'Imagen',
                            'document' => 'Documento',
                            'video' => 'Vídeo',
                            'audio' => 'Audio',
                            'exam' => 'Examen',
                            'link' => 'Enlace',
                            ];
                            @endphp

                            @foreach ($types as $typeValue => $typeLabel)
                            <label class="profile-type-filter">
                                <span>{{ $typeLabel }}</span>

                                <input
                                    type="checkbox"
                                    name="types[]"
                                    value="{{ $typeValue }}"
                                    data-profile-type
                                    @checked(in_array($typeValue, $selectedTypes, true))>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <script type="application/json" id="profile-courses-with-subjects-data">
                        @json($courses)
                    </script>

                    <script>
                        window.profileCoursesWithSubjects = JSON.parse(
                            document.getElementById('profile-courses-with-subjects-data')?.textContent ?? '[]'
                        );
                    </script>
                    @endif

                    <section class="profile-resources" data-profile-results>
                        @if ($isPrivateBlocked ?? false)
                        @include('profile.partials.private-resources')
                        @else
                        @forelse ($resources as $resource)
                        @include('profile.partials.resource-card', ['resource' => $resource])
                        @empty
                        <div class="profile-empty">
                            <h3>No hay recursos todavía</h3>

                            <p>
                                @if (($activeTab ?? 'resources') === 'favorites')
                                Cuando guardes recursos en favoritos, aparecerán aquí.
                                @else
                                Cuando este usuario publique recursos, aparecerán aquí.
                                @endif
                            </p>
                        </div>
                        @endforelse
                        @endif
                    </section>

                    @if (!($isPrivateBlocked ?? false))
                    <div class="profile-pagination" data-profile-pagination>
                        @include('profile.partials.pagination', ['resources' => $resources])
                    </div>
                    @endif
                </div>

                <aside class="profile-sidebar">
                    <section class="profile-about-card">
                        <h2>Sobre mí</h2>

                        @if ($hasAboutInfo)
                        <div class="profile-about-list">
                            @if ($profileDescription !== '')
                            <div class="profile-about-item">
                                <span>Descripción</span>
                                <p>{{ $profileDescription }}</p>
                            </div>
                            @endif

                            @if ($specialization !== '')
                            <div class="profile-about-item">
                                <span>Especialización</span>
                                <p>{{ $specialization }}</p>
                            </div>
                            @endif

                            @if ($institutionName !== '')
                            <div class="profile-about-item">
                                <span>Institución</span>
                                <p>{{ $institutionName }}</p>
                            </div>
                            @endif

                            @if ($institutionLocation !== '')
                            <div class="profile-about-item">
                                <span>Ubicación</span>
                                <p>{{ $institutionLocation }}</p>
                            </div>
                            @endif
                        </div>
                        @else
                        <p class="profile-about-empty">Este usuario todavía no ha añadido información.</p>
                        @endif
                    </section>

                    <section class="profile-suggested-card">
                        <h2>Profesores Sugeridos</h2>

                        @forelse ($suggestedTeachers as $teacher)
                        @php
                        $teacherName = trim(((string) ($teacher->name ?? '')) . ' ' . ((string) ($teacher->surname ?? '')));
                        $teacherDisplayName = $teacherName !== '' ? $teacherName : 'Usuario';
                        @endphp

                        <div class="profile-suggested-teacher">
                            <a class="profile-suggested-link" href="{{ route('profile.show', ['user' => $teacher->nickname]) }}">
                                <x-user-avatar :user="$teacher" alt="Avatar de {{ $teacherDisplayName }}" />

                                <div>
                                    <strong>{{ $teacherDisplayName }}</strong>

                                    <span>
                                        {{ '@' . ($teacher->nickname ?? 'usuario') }}
                                        <x-verified-badge :user="$teacher" />
                                    </span>

                                    <small>{{ $teacher->specialization ?: 'Profesor en Klassify' }}</small>
                                </div>
                            </a>

                            <button
                                type="button"
                                class="profile-follow-btn profile-suggested-follow-btn {{ $teacher->is_following ? 'is-following' : '' }}"
                                data-follow-toggle
                                data-user-id="{{ $teacher->id }}"
                                data-follow-url="{{ route('profile.follow.toggle', ['user' => $teacher->nickname]) }}"
                                aria-pressed="{{ $teacher->is_following ? 'true' : 'false' }}">
                                {{ $teacher->is_following ? 'Siguiendo' : 'Seguir' }}
                            </button>
                        </div>
                        @empty
                        <p class="profile-no-suggestions">No hay profesores sugeridos.</p>
                        @endforelse
                    </section>
                </aside>
            </div>
        </section>
    </div>

</div>
@endsection