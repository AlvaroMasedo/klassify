@extends('layouts.app')

@section('title', 'Klassify - Feed')

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/index.css') }}">
@endsection

@section('content')
<div class="mobile-overlay"></div>
<main class="k-layout">
    <div class="app">
        <aside class="app-left">
            <div class="k-recursosDestacados-card">
                <h2>Recursos Destacados</h2>

                @forelse ($featuredResources as $resource)
                <div class="rd-card">
                    <h3>{{ $resource->title }}</h3>
                    <p class="p-pequeño">Tipo: {{ ucfirst((string) $resource->type) }}</p>

                    <div class="k-interacts">
                        <div class="k-likes">
                            <svg class="icon-heart" data-favorite="false" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#2d1b3d">
                                <path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z" />
                            </svg>
                            <p class="p-numero-pequeño">0</p>
                        </div>

                        <div class="k-comments">
                            <svg class="icon-comment" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#2d1b3d">
                                <path d="M80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z" />
                            </svg>
                            <p class="p-numero-pequeño">0</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="rd-card">
                    <h3>No hay recursos destacados</h3>
                    <p class="p-pequeño">Cuando haya publicaciones reales, aparecerán aquí.</p>
                </div>
                @endforelse
                <div class="view-more">
                    <p>Mostrar más</p>
                </div>
            </div>
        </aside>

        <section class="app-center">
            <div class="forYou-follow-section">
                <span class="tab-indicator" aria-hidden="true"></span>
                <div class="k-forYou tab-active" data-tab="for-you">
                    <h2>Para ti</h2>
                </div>
                <div class="k-follow" data-tab="follow">
                    <h2>Siguiendo</h2>
                </div>
            </div>
            <div class="filter-card">
                <div class="filter-container">
                    <select class="k-select" id="course-filter" name="course">
                        <option value="" selected disabled>Curso</option>
                        @foreach ($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                    <select class="k-select" id="subject-filter" name="subject" disabled>
                        <option value="" selected disabled>Materia</option>
                    </select>

                    <div class="fileTypes-content">
                        <label for="imagen">Imagen</label>
                        <input type="checkbox" name="fileType" id="imagen">
                        <label for="documento">Documento</label>
                        <input type="checkbox" name="fileType" id="documento">
                        <label for="video">Video</label>
                        <input type="checkbox" name="fileType" id="video">
                        <label for="audio">Audio</label>
                        <input type="checkbox" name="fileType" id="audio">
                    </div>
                </div>
            </div>

            <script type="application/json" id="courses-with-subjects-data">
                @json($courses)
            </script>
            <script>
                window.coursesWithSubjects = JSON.parse(
                    document.getElementById('courses-with-subjects-data')?.textContent ?? '[]'
                );
            </script>

            @forelse ($resources as $resource)
            @php
            $userName = trim(((string) ($resource->user->name ?? '')) . ' ' . ((string) ($resource->user->surname ?? '')));
            $resourceUserName = $userName !== '' ? $userName : 'Usuario';
            $resourceNickname = (string) ($resource->user->nickname ?? 'usuario');
            $courseName = (string) ($resource->course->name ?? 'Sin curso');
            $subjectName = (string) ($resource->subject->name ?? 'Sin asignatura');
            $resourceDescription = (string) ($resource->description ?? 'Sin descripción.');
            $resourceUrl = (string) ($resource->display_url ?? $resource->file_url ?? '');
            $resourceMime = strtolower((string) ($resource->mime_type ?? ''));
            $resourcePathFromUrl = (string) (parse_url((string) ($resource->file_url ?? ''), PHP_URL_PATH) ?? '');
            $resourceDetectedName = (string) ($resource->file_name ?? basename($resourcePathFromUrl));
            $resourceExtension = strtolower(pathinfo($resourceDetectedName, PATHINFO_EXTENSION));
            $isImage = str_starts_with($resourceMime, 'image/');
            $isVideo = str_starts_with($resourceMime, 'video/');
            $isAudio = str_starts_with($resourceMime, 'audio/')
            || in_array($resourceExtension, ['mp3', 'wav', 'ogg', 'm4a', 'aac', 'flac'], true);
            $isPdf = $resourceMime === 'application/pdf';
            $isPreviewableImage = in_array($resourceMime, ['image/png', 'image/jpeg'], true)
            || in_array($resourceExtension, ['png', 'jpeg', 'jpg'], true);
            $isPreviewableVideo = $resourceMime === 'video/mp4' || $resourceExtension === 'mp4';
            $isPreviewablePdf = $isPdf || $resourceExtension === 'pdf';
            $isPreviewable = !empty($resourceUrl) && !$isAudio;
            $previewKind = $isAudio
            ? 'audio'
            : ($isPreviewableImage
            ? 'image'
            : ($isPreviewableVideo
            ? 'video'
            : ($isPreviewablePdf ? 'pdf' : 'document')));
            $previewLabel = $isPreviewableImage
            ? 'Visualizar imagen'
            : ($isPreviewablePdf ? 'Visualizar documento' : 'Visualizar archivo');
            $resourceFileName = $resourceDetectedName !== '' ? $resourceDetectedName : 'Documento';
            $resourceExtensionLabel = strtoupper($resourceExtension !== '' ? $resourceExtension : 'FILE');
            $previewOrientationClass = $isPreviewablePdf || in_array($resourceExtension, ['doc', 'docx', 'odt', 'rtf', 'txt', 'ppt', 'pptx'], true)
            ? 'resource-preview-thumb--portrait'
            : 'resource-preview-thumb--landscape';
            $isResourceAuthor = auth()->check() && (int) auth()->id() === (int) $resource->user_id;
            @endphp
            <div class="recurs-card">
                <div class="recurs-header">
                    <div class="recurs-user-info">
                        <div class="recurs-avatar">
                            <img src="{{ asset('assets/img/default-profile-img.png') }}" alt="Avatar">
                        </div>
                        <div class="recurs-user-details">
                            <div class="recurs-name-container">
                                <span class="recurs-name">{{ $resourceUserName }}</span>
                                <span class="recurs-username">{{ '@' . $resourceNickname }}</span>
                            </div>
                            <p class="recurs-meta">Curso: {{ $courseName }} | Asignatura: {{ $subjectName }}</p>
                        </div>
                    </div>
                    @if ($isResourceAuthor)
                    <div class="recurs-more-container">
                        <button class="recurs-more-btn" type="button" aria-label="Opciones del recurso" data-resource-menu-toggle aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#2d1b3d">
                                <path d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z" />
                            </svg>
                        </button>

                        <div class="recurs-more-menu" hidden data-resource-menu-panel>
                            <a href="{{ route('resources.edit', $resource) }}" class="recurs-more-menu__item">Modificar</a>
                            <form method="POST" action="{{ route('resources.destroy', $resource) }}" data-resource-delete-form>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="recurs-more-menu__item recurs-more-menu__item--danger">Eliminar</button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="recurs-content">
                    <h3 class="recurs-title">
                        <span>{{ $resource->title }}</span>
                        @if (strtolower((string) ($resource->type ?? '')) === 'exam')
                        <span class="recurs-title-badge recurs-title-badge--exam">Examen</span>
                        @endif
                    </h3>
                    <p class="recurs-description">{{ $resourceDescription }}</p>
                </div>

                <div class="recurs-media">
                    <div class="recurs-video-thumbnail">
                        @if ($previewKind === 'document')
                        <div class="resource-document-card">
                            <div class="resource-document-main">
                                <div class="resource-document-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" focusable="false">
                                        <path d="M7 2h7l5 5v13a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Zm6 1.5V8h4.5L13 3.5ZM8 11h8v1.5H8V11Zm0 3h8v1.5H8V14Zm0 3h5v1.5H8V17Z" />
                                    </svg>
                                </div>
                                <div class="resource-document-info">
                                    <p class="resource-document-name" title="{{ $resourceFileName }}">{{ $resourceFileName }}</p>
                                    <p class="resource-document-subtitle">Archivo adjunto listo para descargar</p>
                                </div>
                            </div>
                            <div class="resource-document-actions">
                                <span class="resource-document-type">{{ $resourceExtensionLabel }}</span>
                                <a href="{{ $resourceUrl }}" class="resource-document-download" download>
                                    <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
                                        <path d="M12 3a1 1 0 0 1 1 1v8.17l2.59-2.58a1 1 0 1 1 1.41 1.42l-4.3 4.29a1 1 0 0 1-1.4 0l-4.3-4.29a1 1 0 0 1 1.41-1.42L11 12.17V4a1 1 0 0 1 1-1ZM5 17a1 1 0 0 1 1 1v1h12v-1a1 1 0 1 1 2 0v2a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-2a1 1 0 0 1 1-1Z" />
                                    </svg>
                                    <span>Descargar</span>
                                </a>
                            </div>
                        </div>
                        @elseif ($isPreviewable)
                        @if ($isPreviewablePdf)
                        <button
                            type="button"
                            class="resource-preview-pdf-link resource-preview-trigger"
                            data-preview-url="{{ $resourceUrl }}"
                            data-preview-kind="{{ $previewKind }}"
                            data-preview-title="{{ $resource->title }}"
                            aria-label="Abrir vista previa de {{ $resource->title }}">
                            <span class="resource-preview-pdf-link-text" title="{{ $resourceFileName }}">{{ $resourceFileName }}</span>
                        </button>
                        @else
                        <button
                            type="button"
                            class="resource-preview-thumb resource-preview-trigger {{ $previewOrientationClass }}"
                            data-preview-url="{{ $resourceUrl }}"
                            data-preview-kind="{{ $previewKind }}"
                            data-preview-title="{{ $resource->title }}"
                            aria-label="Abrir vista previa de {{ $resource->title }}">
                            @if ($isPreviewableImage)
                            <img src="{{ $resourceUrl }}" alt="Recurso {{ $resource->title }}" class="resource-preview-media-image">
                            @else
                            @if ($isPreviewableVideo)
                            <video preload="metadata" muted playsinline>
                                <source src="{{ $resourceUrl }}" type="video/mp4">
                            </video>
                            <span class="resource-preview-play" aria-hidden="true">
                                <span class="resource-preview-play-badge">
                                    <svg viewBox="0 0 24 24" focusable="false">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                </span>
                            </span>
                            @else
                            <div class="resource-preview-media-document-placeholder" aria-hidden="true">
                                <span>Documento</span>
                            </div>
                            @endif
                            @endif
                            @if (!$isPreviewableVideo)
                            <span class="resource-preview-doc-hover" aria-hidden="true">{{ $previewLabel }}</span>
                            @endif
                        </button>
                        @endif
                        @elseif ($isImage)
                        <img src="{{ $resourceUrl }}" alt="Recurso {{ $resource->title }}">
                        @elseif ($isVideo)
                        <video controls preload="metadata" style="width:100%;height:auto;display:block;">
                            <source src="{{ $resourceUrl }}" type="{{ $resource->mime_type }}">
                            Tu navegador no soporta vídeo HTML5.
                        </video>
                        @elseif ($isAudio)
                        @if ($resourceMime === 'audio/mpeg' || $resourceExtension === 'mp3')
                        <div class="custom-audio-player" data-audio-player>
                            <audio preload="metadata" data-audio-el>
                                <source src="{{ $resourceUrl }}" type="audio/mpeg">
                                Tu navegador no soporta audio HTML5.
                            </audio>
                            <button type="button" class="custom-audio-toggle" data-audio-toggle aria-label="Reproducir audio">
                                <span data-audio-icon>▶</span>
                            </button>
                            <div class="custom-audio-track-wrap">
                                <span class="custom-audio-time" data-audio-current>0:00</span>
                                <input
                                    type="range"
                                    min="0"
                                    max="100"
                                    value="0"
                                    step="0.1"
                                    class="custom-audio-seek"
                                    data-audio-seek
                                    aria-label="Progreso del audio">
                                <span class="custom-audio-time" data-audio-duration>0:00</span>
                            </div>
                            <div class="custom-audio-volume-wrap">
                                <button type="button" class="custom-audio-volume-btn" data-audio-mute aria-label="Silenciar audio">
                                    <span data-audio-volume-icon>🔊</span>
                                </button>
                                <input
                                    type="range"
                                    min="0"
                                    max="100"
                                    value="100"
                                    step="1"
                                    class="custom-audio-volume"
                                    data-audio-volume
                                    aria-label="Volumen">
                            </div>

                            <a
                                href="{{ $resourceUrl }}"
                                class="resource-document-download"
                                download="{{ $resourceFileName }}"
                                aria-label="Descargar {{ $resourceFileName }}">
                                <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
                                    <path d="M12 3a1 1 0 0 1 1 1v8.17l2.59-2.58a1 1 0 1 1 1.41 1.42l-4.3 4.29a1 1 0 0 1-1.4 0l-4.3-4.29a1 1 0 0 1 1.41-1.42L11 12.17V4a1 1 0 0 1 1-1ZM5 17a1 1 0 0 1 1 1v1h12v-1a1 1 0 1 1 2 0v2a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-2a1 1 0 0 1 1-1Z" />
                                </svg>
                                <span>Descargar</span>
                            </a>
                        </div>
                        @else
                        <div style="padding: 1rem; background: #fff; border-radius: 10px;">
                            <audio controls preload="metadata" style="width:100%;">
                                <source src="{{ $resourceUrl }}" type="{{ $resource->mime_type }}">
                                Tu navegador no soporta audio HTML5.
                            </audio>
                        </div>
                        @endif
                        @else
                        <div style="padding: 1rem; background: #fff; border-radius: 10px;">
                            <a href="{{ $resourceUrl }}" target="_blank" rel="noreferrer">Abrir recurso</a>
                            @if (!empty($resource->file_name))
                            <p style="margin: 0.5rem 0 0;">{{ $resource->file_name }}</p>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                <div class="recurs-interactions">
                    <div class="recurs-action">
                        <svg class="icon-heart" data-favorite="false" xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="#2d1b3d">
                            <path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z" />
                        </svg>
                        <span class="recurs-count">0</span>
                    </div>
                    <div class="recurs-action">
                        <svg class="icon-comment" xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="#2d1b3d">
                            <path d="M80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z" />
                        </svg>
                        <span class="recurs-count">0</span>
                    </div>
                    <div class="recurs-action">
                        <svg class="icon-bookmark" data-saved="false" xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="#2d1b3d">
                            <path d="M200-120v-640q0-33 23.5-56.5T280-840h400q33 0 56.5 23.5T760-760v640L480-240 200-120Zm80-122 200-86 200 86v-518H280v518Zm0-518h400-400Z" />
                        </svg>
                        <span class="recurs-count">0</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="recurs-card">
                <div class="recurs-content">
                    <h3 class="recurs-title">Todavía no hay recursos publicados</h3>
                    <p class="recurs-description">Cuando subas recursos reales, aparecerán aquí en esta misma estructura.</p>
                </div>
            </div>
            @endforelse

            @if ($resources->hasMorePages())
            <div class="feed-load-more">
                <a href="{{ $resources->nextPageUrl() }}" class="feed-load-more-btn" data-feed-load-more>Carga mas recursos</a>
            </div>
            @endif

            <div class="resource-preview-modal" id="resource-preview-modal" hidden>
                <div class="resource-preview-backdrop" data-preview-close="true"></div>
                <div class="resource-preview-dialog" role="dialog" aria-modal="true" aria-labelledby="resource-preview-title">
                    <button type="button" class="resource-preview-close" data-preview-close="true" aria-label="Cerrar vista previa">&times;</button>
                    <h3 class="resource-preview-title" id="resource-preview-title"></h3>
                    <div class="resource-preview-stage" id="resource-preview-stage"></div>
                </div>
            </div>
        </section>

        <aside class="app-right">
            <div class="k-recursosDestacados-card">
                <h2>Profesores Sugeridos</h2>

                <!-- Profesor sugerido -->
                <div class="teacher-card">
                    <div class="teacher-header">
                        <div class="teacher-user-info">
                            <div class="teacher-avatar">
                                <img src="{{ asset('assets/img/default-profile-img.png') }}" alt="Avatar">
                            </div>
                            <div class="teacher-user-details">
                                <div class="teacher-name-container">
                                    <span class="teacher-name">Nombre</span>
                                    <span class="teacher-username">@nick-name</span>
                                    <svg class="verified-badge" xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px" fill="#1DA1F2">
                                        <path d="m344-60-76-128-144-32 14-148-98-112 98-112-14-148 144-32 76-128 136 58 136-58 76 128 144 32-14 148 98 112-98 112 14 148-144 32-76 128-136-58-136 58Zm34-102 102-44 104 44 56-96 110-26-10-112 74-84-74-86 10-112-110-24-58-96-102 44-104-44-56 96-110 24 10 112-74 86 74 84-10 114 110 24 58 96Zm102-318Zm-42 142 226-226-56-58-170 170-86-84-56 56 142 142Z" />
                                    </svg>
                                </div>
                                <p class="teacher-meta">Centro: Institut Sa Palomera </p>
                            </div>
                        </div>
                        <button class="teacher-follow-btn">
                            <span>Seguir</span>
                        </button>
                    </div>
                </div>

                <!-- Profesor sugerido -->
                <div class="teacher-card">
                    <div class="teacher-header">
                        <div class="teacher-user-info">
                            <div class="teacher-avatar">
                                <img src="{{ asset('assets/img/default-profile-img.png') }}" alt="Avatar">
                            </div>
                            <div class="teacher-user-details">
                                <div class="teacher-name-container">
                                    <span class="teacher-name">Nombre</span>
                                    <span class="teacher-username">@nick-name</span>
                                    <svg class="verified-badge" xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px" fill="#1DA1F2">
                                        <path d="m344-60-76-128-144-32 14-148-98-112 98-112-14-148 144-32 76-128 136 58 136-58 76 128 144 32-14 148 98 112-98 112 14 148-144 32-76 128-136-58-136 58Zm34-102 102-44 104 44 56-96 110-26-10-112 74-84-74-86 10-112-110-24-58-96-102 44-104-44-56 96-110 24 10 112-74 86 74 84-10 114 110 24 58 96Zm102-318Zm-42 142 226-226-56-58-170 170-86-84-56 56 142 142Z" />
                                    </svg>
                                </div>
                                <p class="teacher-meta">Centro: Institut Sa Palomera </p>
                            </div>
                        </div>
                        <button class="teacher-follow-btn">
                            <span>Seguir</span>
                        </button>
                    </div>
                </div>

                <!-- Profesor sugerido -->
                <div class="teacher-card">
                    <div class="teacher-header">
                        <div class="teacher-user-info">
                            <div class="teacher-avatar">
                                <img src="{{ asset('assets/img/default-profile-img.png') }}" alt="Avatar">
                            </div>
                            <div class="teacher-user-details">
                                <div class="teacher-name-container">
                                    <span class="teacher-name">Nombre</span>
                                    <span class="teacher-username">@nick-name</span>
                                    <svg class="verified-badge" xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px" fill="#1DA1F2">
                                        <path d="m344-60-76-128-144-32 14-148-98-112 98-112-14-148 144-32 76-128 136 58 136-58 76 128 144 32-14 148 98 112-98 112 14 148-144 32-76 128-136-58-136 58Zm34-102 102-44 104 44 56-96 110-26-10-112 74-84-74-86 10-112-110-24-58-96-102 44-104-44-56 96-110 24 10 112-74 86 74 84-10 114 110 24 58 96Zm102-318Zm-42 142 226-226-56-58-170 170-86-84-56 56 142 142Z" />
                                    </svg>
                                </div>
                                <p class="teacher-meta">Centro: Institut Sa Palomera </p>
                            </div>
                        </div>
                        <button class="teacher-follow-btn">
                            <span>Seguir</span>
                        </button>
                    </div>
                </div>

                <!-- Profesor sugerido -->
                <div class="teacher-card">
                    <div class="teacher-header">
                        <div class="teacher-user-info">
                            <div class="teacher-avatar">
                                <img src="{{ asset('assets/img/default-profile-img.png') }}" alt="Avatar">
                            </div>
                            <div class="teacher-user-details">
                                <div class="teacher-name-container">
                                    <span class="teacher-name">Nombre</span>
                                    <span class="teacher-username">@nick-name</span>
                                    <svg class="verified-badge" xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px" fill="#1DA1F2">
                                        <path d="m344-60-76-128-144-32 14-148-98-112 98-112-14-148 144-32 76-128 136 58 136-58 76 128 144 32-14 148 98 112-98 112 14 148-144 32-76 128-136-58-136 58Zm34-102 102-44 104 44 56-96 110-26-10-112 74-84-74-86 10-112-110-24-58-96-102 44-104-44-56 96-110 24 10 112-74 86 74 84-10 114 110 24 58 96Zm102-318Zm-42 142 226-226-56-58-170 170-86-84-56 56 142 142Z" />
                                    </svg>
                                </div>
                                <p class="teacher-meta">Centro: Institut Sa Palomera </p>
                            </div>
                        </div>
                        <button class="teacher-follow-btn">
                            <span>Seguir</span>
                        </button>
                    </div>
                </div>

                <!-- Profesor sugerido -->
                <div class="teacher-card">
                    <div class="teacher-header">
                        <div class="teacher-user-info">
                            <div class="teacher-avatar">
                                <img src="{{ asset('assets/img/default-profile-img.png') }}" alt="Avatar">
                            </div>
                            <div class="teacher-user-details">
                                <div class="teacher-name-container">
                                    <span class="teacher-name">Nombre</span>
                                    <span class="teacher-username">@nick-name</span>
                                    <svg class="verified-badge" xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px" fill="#1DA1F2">
                                        <path d="m344-60-76-128-144-32 14-148-98-112 98-112-14-148 144-32 76-128 136 58 136-58 76 128 144 32-14 148 98 112-98 112 14 148-144 32-76 128-136-58-136 58Zm34-102 102-44 104 44 56-96 110-26-10-112 74-84-74-86 10-112-110-24-58-96-102 44-104-44-56 96-110 24 10 112-74 86 74 84-10 114 110 24 58 96Zm102-318Zm-42 142 226-226-56-58-170 170-86-84-56 56 142 142Z" />
                                    </svg>
                                </div>
                                <p class="teacher-meta">Centro: Institut Sa Palomera </p>
                            </div>
                        </div>
                        <button class="teacher-follow-btn">
                            <span>Seguir</span>
                        </button>
                    </div>
                </div>
                <div class="view-more">
                    <p>Mostrar más</p>
                </div>
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
        </aside>
    </div>
</main>
@endsection