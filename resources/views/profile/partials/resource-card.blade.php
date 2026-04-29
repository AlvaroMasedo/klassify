@php
$type = strtolower((string) ($resource->type ?? 'document'));

$typeLabels = [
'document' => 'Documento',
'video' => 'Vídeo',
'audio' => 'Audio',
'image' => 'Imagen',
'exam' => 'Examen',
'link' => 'Enlace',
];

$typeLabel = $typeLabels[$type] ?? ucfirst($type);
$description = trim((string) ($resource->description ?? ''));
$likesCount = $resource->likes_count ?? 0;
$commentsCount = $resource->comments_count ?? 0;
$courseName = $resource->course->name ?? 'Sin curso';
$subjectName = $resource->subject->name ?? 'Sin asignatura';
@endphp

<article class="profile-resource-card">
    <a href="{{ route('resources.show', $resource) }}" class="profile-resource-link">
        <div class="profile-resource-top">
            <div class="profile-resource-meta">
                <span>{{ $courseName }}</span>
                <span>·</span>
                <span>{{ $subjectName }}</span>
            </div>

            <span class="profile-resource-type profile-resource-type--{{ $type }}">
                {{ $typeLabel }}
            </span>
        </div>

        <h3>{{ $resource->title }}</h3>

        <p class="profile-resource-description">
            {{ $description !== '' ? $description : 'Sin descripción.' }}
        </p>

        <div class="profile-resource-footer">
            <span class="profile-resource-stat">
                <svg viewBox="0 -960 960 960" aria-hidden="true">
                    <path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Z" />
                </svg>
                {{ $likesCount }}
            </span>

            <span class="profile-resource-stat">
                <svg viewBox="0 -960 960 960" aria-hidden="true">
                    <path d="M80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Z" />
                </svg>
                {{ $commentsCount }}
            </span>

            <span class="profile-resource-date">
                {{ $resource->updated_at ? $resource->updated_at->locale('es')->diffForHumans() : 'Hace poco' }}
            </span>
        </div>
    </a>
</article>