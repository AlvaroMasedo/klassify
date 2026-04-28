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

        <section class="app-center">
            <!-- Botón volver al feed -->
            <div class="resource-detail-header">
                <a href="{{ route('feed') }}" class="back-button" aria-label="Volver al feed">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#2d1b3d">
                        <path d="M400-80 0-480l400-400 56 57-343 343h687v80H113l343 343-56 57Z" />
                    </svg>
                </a>
                <h3 class="resource-detail-title">Recurso</h3>
            </div>

            <!-- Tarjeta del recurso en vista detalle -->
            @include('feed.partials.resource-detail-card', ['resource' => $resource])

            <!-- Sección de comentarios -->
            <div class="comments-section">
                <!-- Formulario para comentar -->
                @auth
                <div class="comment-form-container">
                    <h4 class="comments-title">Comentarios</h4>
                    <form class="comment-form" id="comment-form" method="POST" action="{{ route('resources.comments.store', $resource) }}">
                        @csrf
                        <div class="comment-form-input-wrapper">
                            <textarea
                                class="comment-form-input"
                                name="comment"
                                placeholder="Escribe tu comentario..."
                                maxlength="750"
                                rows="3"></textarea>
                            <span class="comment-char-count"><span id="char-count">0</span>/750</span>
                        </div>
                        @error('comment')
                        <div class="form-error">{{ $message }}</div>
                        @enderror
                        <button type="submit" class="comment-form-submit">Comentar</button>
                    </form>
                </div>

                <!-- Lista de comentarios -->
                <div class="comments-list" id="comments-list">
                    @forelse ($comments as $comment)
                    @include('feed.partials.comment', ['comment' => $comment])
                    @empty
                    <div class="comments-empty">
                        <p>No hay comentarios todavía. ¡Sé el primero en comentar!</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </section>

        <aside class="app-right">
            @include('feed.partials.sidebar')
        </aside>
    </div>
</main>

<script>
    // Script para actualizar contador de caracteres
    const textarea = document.getElementById('comment-form')?.querySelector('textarea');
    const charCount = document.getElementById('char-count');

    if (textarea && charCount) {
        textarea.addEventListener('input', () => {
            charCount.textContent = textarea.value.length;
        });
    }

    // Script para enviar comentario con AJAX
    const commentForm = document.getElementById('comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            const textarea = commentForm.querySelector('textarea');
            const comment = textarea.value.trim();

            if (!comment) {
                alert('El comentario no puede estar vacío.');
                return;
            }

            const submitBtn = commentForm.querySelector('.comment-form-submit');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Publicando...';

            try {
                const response = await fetch(commentForm.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': document.querySelector('input[name="_token"]').value,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ comment }),
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Añadir el nuevo comentario a la lista
                    const commentsList = document.getElementById('comments-list');
                    const emptyMessage = commentsList?.querySelector('.comments-empty');

                    if (emptyMessage) {
                        emptyMessage.remove();
                    }

                    commentsList.insertAdjacentHTML('afterbegin', data.html);

                    // Limpiar el formulario
                    textarea.value = '';
                    charCount.textContent = '0';
                } else if (response.status === 422) {
                    // Error de validación
                    const errors = data.errors || {};
                    const errorMessages = Object.values(errors).flat();
                    alert('Error: ' + (errorMessages[0] || 'No se pudo publicar el comentario.'));
                } else {
                    alert(data.message || 'Hubo un error al publicar el comentario.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión. Inténtalo de nuevo.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    }
</script>

<style scoped>
    .resource-detail-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1rem 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .back-button {
        background: none;
        border: none;
        cursor: pointer;
        color: #2d1b3d;
        display: flex;
        align-items: center;
        padding: 0.5rem;
        border-radius: 50%;
        transition: background-color 0.2s ease;
    }

    .back-button:hover {
        background-color: #f5f5f5;
    }

    .resource-detail-title {
        font-family: "Open Sans", sans-serif;
        color: #583473;
        font-size: 1.5rem;
        margin: 0;
    }

    .comments-section {
        margin-top: 2rem;
    }

    .comments-title {
        font-family: "Open Sans", sans-serif;
        color: #583473;
        font-size: 1.2rem;
        margin-bottom: 1rem;
    }

    .comment-form-container {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: #f9f7fc;
        border-radius: 12px;
    }

    .comment-form-input-wrapper {
        position: relative;
        margin-bottom: 0.5rem;
    }

    .comment-form-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d5d0e0;
        border-radius: 8px;
        font-family: "Open Sans", sans-serif;
        font-size: 0.95rem;
        resize: vertical;
        box-sizing: border-box;
    }

    .comment-form-input:focus {
        outline: none;
        border-color: #583473;
        box-shadow: 0 0 0 3px rgba(88, 52, 115, 0.1);
    }

    .comment-char-count {
        display: block;
        text-align: right;
        font-size: 0.75rem;
        color: #999;
        margin-top: 0.25rem;
    }

    .form-error {
        color: #c41e3a;
        font-size: 0.85rem;
        margin: 0.5rem 0;
    }

    .comment-form-submit {
        background: linear-gradient(90deg, #583473 0%, #A485BC 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-family: "Open Sans", sans-serif;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .comment-form-submit:hover:not(:disabled) {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(88, 52, 115, 0.3);
    }

    .comment-form-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .comments-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .comment-item {
        padding: 1rem;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
    }

    .comment-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
    }

    .comment-user-info {
        display: flex;
        gap: 0.75rem;
    }

    .comment-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
    }

    .comment-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .comment-user-details {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .comment-user-name {
        font-weight: 600;
        font-size: 0.95rem;
        color: #2d1b3d;
    }

    .comment-user-username {
        font-size: 0.85rem;
        color: #999;
    }

    .comment-date {
        font-size: 0.85rem;
        color: #999;
    }

    .comment-body {
        padding-left: 3.5rem;
    }

    .comment-text {
        font-size: 0.95rem;
        color: #3f2453;
        line-height: 1.5;
        margin: 0;
        word-wrap: break-word;
    }

    .comments-empty {
        text-align: center;
        padding: 2rem;
        color: #999;
        font-size: 0.95rem;
    }
</style>
@endsection
