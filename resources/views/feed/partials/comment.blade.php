@php
$userName = trim(((string) ($comment->user->name ?? '')) . ' ' . ((string) ($comment->user->surname ?? '')));
$userDisplayName = $userName !== '' ? $userName : 'Usuario';
$userNickname = (string) ($comment->user->nickname ?? 'usuario');
$commentText = (string) ($comment->comment ?? '');
$createdAt = $comment->created_at?->format('d M Y H:i') ?? 'Hace poco';
@endphp

<div class="comment-item">
    <div class="comment-header">
        <div class="comment-user-info">
            <div class="comment-avatar">
                <img src="{{ asset('assets/img/default-profile-img.png') }}" alt="Avatar de {{ $userDisplayName }}">
            </div>
            <div class="comment-user-details">
                <span class="comment-user-name">{{ $userDisplayName }}</span>
                <span class="comment-user-username">@{{ $userNickname }}</span>
            </div>
        </div>
        <div class="comment-actions" aria-hidden="true">
            <button type="button" class="comment-more-btn" tabindex="-1" aria-label="Opciones del comentario" style="background:none;border:0;padding:0;display:flex;align-items:center;justify-content:center;cursor:default;">
                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#2d1b3d">
                    <path d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z" />
                </svg>
            </button>
            <span class="comment-date">{{ $createdAt }}</span>
        </div>
    </div>
    <div class="comment-body">
        <p class="comment-text">{{ $commentText }}</p>
    </div>
</div>
