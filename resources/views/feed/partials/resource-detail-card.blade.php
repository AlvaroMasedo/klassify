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
$isImage = str_starts_with($resourceMime, 'image/')
|| in_array($resourceExtension, ['png', 'jpeg', 'jpg', 'webp'], true);

$isVideo = str_starts_with($resourceMime, 'video/')
|| in_array($resourceExtension, ['mp4', 'webm', 'ogg'], true);

$isAudio = str_starts_with($resourceMime, 'audio/')
|| in_array($resourceExtension, ['mp3', 'wav', 'ogg', 'm4a', 'aac', 'flac'], true);

$isPdf = $resourceMime === 'application/pdf'
|| $resourceExtension === 'pdf';
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
$currentUser = auth()->user();
$currentUserRole = strtoupper((string) ($currentUser?->role ?? ''));

$isResourceAuthor = auth()->check() && (int) auth()->id() === (int) $resource->user_id;
$isAdmin = $currentUserRole === 'ADMIN';

$canEditResource = $isResourceAuthor;
$canDeleteResource = $isResourceAuthor || $isAdmin;
$isResourceOwner = (int) auth()->id() === (int) $resource->user_id;
$canReportResource = auth()->check() && !$isResourceOwner;
$canShowResourceMenu = $canReportResource || $canEditResource || $canDeleteResource;
$updatedDate = $resource->updated_at?->format('d M Y') ?? $resource->created_at?->format('d M Y') ?? 'Hace poco';
$favoritesCount = (int) ($resource->favorites_count ?? 0);
$isFavorited = (bool) ($resource->is_favorited ?? false);
@endphp

<div class="recurs-card">
    <div class="recurs-header">
        <div class="recurs-user-info">
            <div class="recurs-avatar">
                <x-user-avatar :user="$resource->user" alt="Avatar de {{ $resourceUserName }}" />
            </div>
            <div class="recurs-user-details">
                <div class="recurs-name-container">
                    <x-user-profile-link :user="$resource->user" class="recurs-user-name">
                        {{ $resourceUserName }}
                    </x-user-profile-link>
                    <span class="recurs-user-username">
                        {{ '@' . ($resource->user->nickname ?? 'usuario') }}
                        <x-verified-badge :user="$resource->user" />
                    </span>
                </div>
                <p class="recurs-meta">Curso: {{ $courseName }} | Asignatura: {{ $subjectName }}</p>
            </div>
        </div>
        @if ($canShowResourceMenu)
        <div class="recurs-more-container">
            <button class="recurs-more-btn" type="button" aria-label="Opciones del recurso" data-resource-menu-toggle aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#2d1b3d">
                    <path d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z" />
                </svg>
            </button>

            <div class="recurs-more-menu" hidden data-resource-menu-panel>
                @if ($canReportResource)
                <button
                    type="button"
                    class="recurs-more-menu__item"
                    data-report-open
                    data-report-type="resource"
                    data-report-title="{{ $resource->title }}"
                    data-report-url="{{ route('resources.report.store', $resource) }}">
                    Denunciar
                </button>
                @endif
                @if ($canEditResource)
                <a href="{{ route('resources.edit', $resource) }}" class="recurs-more-menu__item">
                    Editar
                </a>
                @endif

                @if ($canDeleteResource)
                <form method="POST" action="{{ route('resources.destroy', $resource) }}" data-resource-delete-form>
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="recurs-more-menu__item recurs-more-menu__item--danger">
                        Eliminar
                    </button>
                </form>
                @endif
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

    <div class="recurs-media recurs-media--detail">
        <div class="recurs-video-thumbnail recurs-video-thumbnail--detail">

            @if (empty($resourceUrl))
            <div class="resource-detail-fallback">
                <p>No hay archivo disponible para este recurso.</p>
            </div>

            @elseif ($isPdf)
            <button
                type="button"
                class="resource-preview-pdf-link resource-preview-trigger"
                data-preview-url="{{ $resourceUrl }}"
                data-preview-kind="{{ $previewKind }}"
                data-preview-title="{{ $resource->title }}"
                aria-label="Abrir vista previa de {{ $resource->title }}">
                <span class="resource-preview-pdf-link-text" title="{{ $resourceFileName }}">{{ $resourceFileName }}</span>
            </button>

            @elseif ($isImage)
            <button
                type="button"
                class="resource-preview-thumb resource-preview-trigger {{ $previewOrientationClass }}"
                data-preview-url="{{ $resourceUrl }}"
                data-preview-kind="image"
                data-preview-title="{{ $resource->title }}"
                aria-label="Abrir imagen de {{ $resource->title }}">
                <img
                    src="{{ $resourceUrl }}"
                    alt="Recurso {{ $resource->title }}"
                    class="resource-preview-media-image">

                <span class="resource-preview-doc-hover" aria-hidden="true">
                    Visualizar imagen
                </span>
            </button>

            @elseif ($isVideo)
            <video class="resource-detail-video" controls preload="metadata" playsinline>
                <source src="{{ $resourceUrl }}" type="{{ $resourceMime ?: 'video/mp4' }}">
                Tu navegador no soporta vídeo HTML5.
            </video>

            @elseif ($isAudio)
            <div class="custom-audio-player resource-detail-audio-player" data-audio-player>
                <audio preload="metadata" data-audio-el>
                    <source src="{{ $resourceUrl }}" type="{{ $resourceMime ?: 'audio/mpeg' }}">
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
            <div class="resource-document-card">
                <div class="resource-document-main">
                    <div class="resource-document-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" focusable="false">
                            <path d="M7 2h7l5 5v13a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Zm6 1.5V8h4.5L13 3.5ZM8 11h8v1.5H8V11Zm0 3h8v1.5H8V14Zm0 3h5v1.5H8V17Z" />
                        </svg>
                    </div>

                    <div class="resource-document-info">
                        <p class="resource-document-name" title="{{ $resourceFileName }}">{{ $resourceFileName }}</p>
                        <p class="resource-document-subtitle">Este tipo de archivo se puede abrir o descargar</p>
                    </div>
                </div>

                <div class="resource-document-actions">
                    <span class="resource-document-type">{{ $resourceExtensionLabel }}</span>

                    <a href="{{ $resourceUrl }}" class="resource-document-download" download="{{ $resourceFileName }}">
                        <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
                            <path d="M12 3a1 1 0 0 1 1 1v8.17l2.59-2.58a1 1 0 1 1 1.41 1.42l-4.3 4.29a1 1 0 0 1-1.4 0l-4.3-4.29a1 1 0 0 1 1.41-1.42L11 12.17V4a1 1 0 0 1 1-1ZM5 17a1 1 0 0 1 1 1v1h12v-1a1 1 0 1 1 2 0v2a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-2a1 1 0 0 1 1-1Z" />
                        </svg>
                        <span>Descargar</span>
                    </a>
                </div>
            </div>
            @endif

        </div>
    </div>

    <div class="recurs-interactions">
        @php
        $likesCount = (int) ($resource->likes_count ?? 0);
        $isLiked = (bool) ($resource->is_liked ?? false);
        @endphp

        <button
            type="button"
            class="recurs-action recurs-like-btn {{ $isLiked ? 'is-liked' : '' }}"
            data-like-toggle
            data-resource-id="{{ $resource->id }}"
            data-like-url="{{ route('resources.like.toggle', $resource) }}"
            aria-pressed="{{ $isLiked ? 'true' : 'false' }}"
            aria-label="{{ $isLiked ? 'Quitar like' : 'Dar like' }}">
            <svg class="icon-heart" xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" aria-hidden="true" focusable="false">
                <path d="M480-120l-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Z" />
            </svg>
            <span
                class="recurs-count"
                data-likes-count-for="{{ $resource->id }}">
                {{ $likesCount }}
            </span>
        </button>
        <button
            type="button"
            class="recurs-action recurs-action-btn recurs-favorite-btn {{ $isFavorited ? 'is-favorited' : '' }}"
            data-favorite-toggle
            data-resource-id="{{ $resource->id }}"
            data-favorite-url="{{ route('resources.favorite.toggle', $resource) }}"
            aria-pressed="{{ $isFavorited ? 'true' : 'false' }}"
            aria-label="{{ $isFavorited ? 'Quitar de favoritos' : 'Guardar en favoritos' }}">
            <svg class="icon-bookmark" xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" aria-hidden="true" focusable="false">
                <path d="M200-120v-640q0-33 23.5-56.5T280-840h400q33 0 56.5 23.5T760-760v640L480-240 200-120Z" />
            </svg>
            <span
                class="recurs-count"
                data-favorites-count-for="{{ $resource->id }}">
                {{ $favoritesCount }}
            </span>
        </button>
        <div class="recurs-update-date-inline">Actualizado: {{ $updatedDate }}</div>
    </div>
</div>

<style scoped>
    .recurs-update-date {
        font-family: "Open Sans", sans-serif;
        font-size: 0.85rem;
        color: #999;
        margin-top: 0.5rem;
        margin-bottom: 0;
    }

    .recurs-update-date-inline {
        font-family: "Open Sans", sans-serif;
        font-size: 0.85rem;
        color: #999;
        align-self: center;
        margin-left: auto;
    }
</style>