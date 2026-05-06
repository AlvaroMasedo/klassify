@php
$userName = trim(((string) ($comment->user->name ?? '')) . ' ' . ((string) ($comment->user->surname ?? '')));
$userDisplayName = $userName !== '' ? $userName : 'Usuario';
$userNickname = (string) ($comment->user->nickname ?? 'usuario');
$commentText = (string) ($comment->comment ?? '');
$createdAt = $comment->created_at?->format('d M Y H:i') ?? 'Hace poco';

$currentUser = auth()->user();
$isAdmin = strtoupper((string) ($currentUser?->role ?? '')) === 'ADMIN';
$isOwner = (int) auth()->id() === (int) $comment->user_id;
$canDeleteComment = $isAdmin || $isOwner;
$canReportComment = auth()->check() && !$isOwner;
$canShowCommentMenu = $canReportComment || $canDeleteComment;
@endphp

<div class="comment-item" data-comment-item data-comment-id="{{ $comment->id }}">
    <div class="comment-header">
        <div class="comment-user-info">
            <div class="comment-avatar">
                <x-user-avatar :user="$comment->user" alt="Avatar de {{ $userDisplayName }}" />
            </div>

            <div class="comment-user-details">
                <x-user-profile-link :user="$comment->user" class="comment-user-name">
                    {{ $userDisplayName }}
                </x-user-profile-link>
                <span class="comment-user-username">
                    {{ '@' . $userNickname }}
                    <x-verified-badge :user="$comment->user" />
                </span>
            </div>
        </div>

        <div class="comment-actions">
            @if ($canShowCommentMenu)
            <div class="comment-options">
                <button
                    type="button"
                    class="comment-more-btn"
                    data-comment-menu-toggle
                    aria-label="Opciones del comentario"
                    aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#2d1b3d">
                        <path d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z" />
                    </svg>
                </button>

                <div class="comment-options-menu" data-comment-options-menu>
                    @if ($canReportComment)
                    <button
                        type="button"
                        class="recurs-more-menu__item"
                        data-report-open
                        data-report-type="comment"
                        data-report-title="comentario"
                        data-report-url="{{ route('resources.comments.report.store', $comment) }}">
                        Denunciar
                    </button>
                    @endif
                    @if ($canDeleteComment)
                    <button
                        type="button"
                        class="comment-delete-option"
                        data-comment-delete
                        data-delete-url="{{ route('resources.comments.destroy', ['resource' => $comment->resource_id, 'comment' => $comment->id]) }}">
                        Eliminar
                    </button>
                    @endif
                </div>
            </div>
            @endif

            <span class="comment-date">{{ $createdAt }}</span>
        </div>
    </div>

    <div class="comment-body">
        <p class="comment-text">{{ $commentText }}</p>
    </div>
</div>