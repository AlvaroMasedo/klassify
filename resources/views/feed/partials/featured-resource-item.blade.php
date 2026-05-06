@php
    $likesCount = (int) ($resource->likes_count ?? 0);
    $commentsCount = (int) ($resource->comments_count ?? 0);
    $isLiked = (bool) ($resource->is_liked ?? false);
    $resourceShowUrl = route('resources.show', $resource);
@endphp

<div class="rd-card" data-resource-id="{{ $resource->id }}">
    <h3>
        <a class="rd-card-title-link" href="{{ $resourceShowUrl }}">
            {{ $resource->title }}
        </a>
    </h3>

    <p class="p-pequeño">Tipo: {{ ucfirst((string) $resource->type) }}</p>

    <div class="k-interacts">
        <button
            type="button"
            class="k-likes rd-action-btn recurs-like-btn {{ $isLiked ? 'is-liked' : '' }}"
            data-like-toggle
            data-resource-id="{{ $resource->id }}"
            data-like-url="{{ route('resources.like.toggle', $resource) }}"
            aria-pressed="{{ $isLiked ? 'true' : 'false' }}">
            <svg class="icon-heart" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" aria-hidden="true" focusable="false">
                <path d="M480-120l-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Z" />
            </svg>

            <span class="p-numero-pequeño" data-likes-count-for="{{ $resource->id }}">
                {{ $likesCount }}
            </span>
        </button>

        <a
            class="k-comments rd-comment-link"
            href="{{ $resourceShowUrl }}?focus=comment">
            <svg class="icon-comment" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#2d1b3d" aria-hidden="true" focusable="false">
                <path d="M80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z" />
            </svg>

            <span class="p-numero-pequeño">
                {{ $commentsCount }}
            </span>
        </a>
    </div>
</div>