@extends('layouts.app')

@section('page', 'feed')

@section('title', 'Klassify - ' . ($resource->title ?? 'Recurso'))

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

                @include('feed.partials.featured-resources')
            </div>
        </aside>

        <section class="app-center resource-detail-center">
            <div class="resource-detail-header">
                <a href="{{ route('feed') }}" class="back-button" aria-label="Volver al feed">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#2d1b3d">
                        <path d="M400-80 0-480l400-400 56 57-343 343h687v80H113l343 343-56 57Z" />
                    </svg>
                </a>
                <h3 class="resource-detail-title">Recurso</h3>
            </div>

            @include('feed.partials.resource-detail-card', ['resource' => $resource])
            @include('feed.partials.resource-preview-modal')

            <div class="comments-section">
                <div class="comment-form-container">
                    <h4 class="comments-title">Comentarios</h4>

                    <div class="comment-form-author">
                        <div class="comment-avatar">
                            <x-user-avatar
                                :user="auth()->user()"
                                alt="Avatar de {{ trim(((string) (auth()->user()?->name ?? '')) . ' ' . ((string) (auth()->user()?->surname ?? ''))) ?: 'Usuario' }}" />
                        </div>
                        <div class="comment-form-author-info">
                            <span class="comment-user-name">{{ trim(((string) (auth()->user()?->name ?? '')) . ' ' . ((string) (auth()->user()?->surname ?? ''))) ?: 'Usuario' }}</span>
                            <span class="comment-user-username">{{ '@' . (auth()->user()?->nickname ?? 'usuario') }}</span>
                        </div>
                    </div>

                    <form
                        class="comment-form"
                        id="comment-form"
                        method="POST"
                        action="{{ route('resources.comments.store', $resource) }}"
                        data-resource-id="{{ $resource->id }}"
                        data-store-url="{{ route('resources.comments.store', $resource) }}">
                        @csrf
                        <div class="comment-form-input-wrapper">
                            <textarea
                                class="comment-form-input"
                                name="comment"
                                placeholder="Escribe tu comentario..."
                                maxlength="750"
                                rows="3"
                                data-comment-input></textarea>
                            <span class="comment-char-count"><span id="char-count">0</span>/750</span>
                        </div>
                        @error('comment')
                        <div class="form-error">{{ $message }}</div>
                        @enderror
                        <button type="submit" class="comment-form-submit">Enviar</button>
                    </form>
                </div>

                <div class="comments-list" id="comments-list">
                    @forelse ($comments as $comment)
                    @include('feed.partials.comment', ['comment' => $comment])
                    @empty
                    <div class="comments-empty">
                        <p>Todavía no hay comentarios. Sé el primero en comentar.</p>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="comment-delete-modal" id="comment-delete-modal" hidden>
                <div class="comment-delete-backdrop" data-comment-delete-cancel></div>

                <div class="comment-delete-card" role="dialog" aria-modal="true" aria-labelledby="comment-delete-title">
                    <h4 id="comment-delete-title">¿Eliminar comentario?</h4>

                    <p>Esta acción eliminará tu comentario. No podrás deshacerlo.</p>

                    <div class="comment-delete-error" data-comment-delete-error hidden></div>

                    <div class="comment-delete-actions">
                        <button type="button" class="comment-delete-cancel" data-comment-delete-cancel>
                            Cancelar
                        </button>

                        <button type="button" class="comment-delete-confirm" data-comment-delete-confirm>
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <aside class="app-right">
            @include('feed.partials.sidebar')
        </aside>
    </div>
</main>

<script>
    /**
     * Maneja el focus en el textarea de comentarios si existe ?focus=comment
     */
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.get('focus') === 'comment') {
            const commentInput = document.querySelector('[data-comment-input]');

            if (commentInput) {
                // Hacer focus en el textarea
                commentInput.focus();

                // Scroll suave hacia la caja de comentario
                const commentSection = commentInput.closest('.comment-form-container');
                if (commentSection) {
                    commentSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }
        }
    });
</script>
@include('feed.partials.favorite-toast')
@endsection