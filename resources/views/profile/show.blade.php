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

$profileDescription = trim((string) ($profileUser->description ?? $profileUser->bio ?? ''));

$specialization = trim((string) ($profileUser->specialization ?? ''));

$institutionName = trim((string) data_get($profileUser, 'institution.name', ''));

$institutionLocation = trim((string) (
data_get($profileUser, 'institution.location')
?? data_get($profileUser, 'institution.city')
?? data_get($profileUser, 'location')
?? data_get($profileUser, 'city')
?? ''
));

$hasAboutInfo = $specialization !== ''
|| $institutionName !== ''
|| $institutionLocation !== '';
@endphp

<div class="profile-page">
    <div class="profile-shell">
        <section class="profile-card">
            <div class="profile-layout">
                <div class="profile-main">
                    <header class="profile-hero">
                        <div class="profile-avatar">
                            <img src="{{ asset('assets/img/default-profile-img.png') }}" alt="Avatar de {{ $displayName }}">
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
                                    <span class="profile-followers">{{ $followersCount }} Seguidores</span>

                                    @if ($isOwner)
                                    <button type="button" class="profile-edit-btn">
                                        Editar perfil
                                    </button>
                                    @else
                                    <button type="button" class="profile-follow-btn">
                                        Seguir
                                    </button>
                                    @endif
                                </div>
                            </div>

                            <div class="profile-meta">
                                <span>{{ $profileSubtitle }}</span>
                            </div>

                            <p class="profile-location">
                                {{ $profileUser->institution->name ?? 'Centro no indicado' }}
                            </p>

                            @if ($profileDescription !== '')
                            <p class="profile-bio-mobile">{{ $profileDescription }}</p>
                            @endif
                        </div>
                    </header>

                    <nav class="profile-tabs" aria-label="Secciones del perfil">
                        <a href="#" class="profile-tab is-active">Recursos</a>
                        <a href="#" class="profile-tab">Favoritos</a>
                        <a href="#" class="profile-tab">Calendario</a>
                    </nav>

                    <form class="profile-filters" method="GET" action="{{ route('profile.show', $profileUser) }}">
                        <div class="profile-filter-group">
                            <select name="course_id" class="profile-select">
                                <option value="">Curso</option>
                                @foreach ($courses as $course)
                                <option value="{{ $course->id }}" @selected((int) $selectedCourseId===(int) $course->id)>
                                    {{ $course->name }}
                                </option>
                                @endforeach
                            </select>

                            <select name="subject_id" class="profile-select">
                                <option value="">Asignatura</option>
                                @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" @selected((int) $selectedSubjectId===(int) $subject->id)>
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
                                    @checked(in_array($typeValue, $selectedTypes, true))>
                            </label>
                            @endforeach
                        </div>

                        <div class="profile-filter-actions">
                            <button type="submit" class="profile-apply-filters">
                                Aplicar filtros
                            </button>

                            <a href="{{ route('profile.show', $profileUser) }}" class="profile-clear-filters">
                                Eliminar filtros
                            </a>
                        </div>
                    </form>

                    <section class="profile-resources">
                        @forelse ($resources as $resource)
                        @include('profile.partials.resource-card', ['resource' => $resource])
                        @empty
                        <div class="profile-empty">
                            <h3>No hay recursos todavía</h3>
                            <p>Cuando este usuario publique recursos, aparecerán aquí.</p>
                        </div>
                        @endforelse
                    </section>

                    @if ($resources->hasPages())
                    <div class="profile-pagination">
                        {{ $resources->links() }}
                    </div>
                    @endif
                </div>

                <aside class="profile-sidebar">
                    <section class="profile-about-card">
                        <h2>Sobre mí</h2>

                        @if ($hasAboutInfo)
                        <div class="profile-about-list">
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
                        @endif
                    </section>

                    <section class="profile-suggested-card">
                        <h2>Profesores Sugeridos</h2>

                        @forelse ($suggestedTeachers as $teacher)
                        @php
                        $teacherName = trim(((string) ($teacher->name ?? '')) . ' ' . ((string) ($teacher->surname ?? '')));
                        $teacherDisplayName = $teacherName !== '' ? $teacherName : 'Usuario';
                        @endphp

                        <a class="profile-suggested-teacher" href="{{ route('profile.show', $teacher) }}">
                            <img src="{{ asset('assets/img/default-profile-img.png') }}" alt="Avatar de {{ $teacherDisplayName }}">

                            <div>
                                <strong>{{ $teacherDisplayName }}</strong>
                                <span>
                                    {{ '@' . ($teacher->nickname ?? 'usuario') }}
                                    <x-verified-badge :user="$teacher" />
                                </span>
                                <small>{{ $teacher->specialization ?: 'Profesor en Klassify' }}</small>
                            </div>

                            <span class="profile-suggested-plus">+</span>
                        </a>
                        @empty
                        <p class="profile-no-suggestions">No hay profesores sugeridos.</p>
                        @endforelse
                    </section>
                </aside>
            </div>
        </section>
    </div>

    @include('layouts.partials.footer')
</div>
@endsection