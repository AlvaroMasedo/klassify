/**
 * Sistema de comentarios por AJAX para la vista detalle del recurso
 */
export function initComments() {
    // Guard para evitar duplicar listeners
    if (window.__feedCommentsInitialized) {
        return;
    }
    window.__feedCommentsInitialized = true;

    const commentForm = document.getElementById('comment-form');
    const commentInput = commentForm?.querySelector('[data-comment-input]');
    const charCount = document.getElementById('char-count');
    const commentsList = document.getElementById('comments-list');

    if (!commentForm || !commentInput || !commentsList) {
        return;
    }

    /**
     * Obtiene el token CSRF del formulario
     */
    const getCsrfToken = () => {
        const tokenInput = commentForm.querySelector('input[name="_token"]');
        return tokenInput?.value || '';
    };

    /**
     * Obtiene la URL para enviar el comentario
     */
    const getStoreUrl = () => {
        return commentForm.getAttribute('data-store-url') || commentForm.getAttribute('action') || '';
    };

    /**
     * Obtiene el texto del formulario de error si existe
     */
    const getErrorContainer = () => {
        return commentForm.querySelector('.form-error');
    };

    /**
     * Limpia los mensajes de error del formulario
     */
    const clearErrors = () => {
        const errorContainer = getErrorContainer();
        if (errorContainer) {
            errorContainer.remove();
        }
    };

    /**
     * Muestra un mensaje de error en el formulario
     */
    const showError = (message) => {
        clearErrors();
        const errorDiv = document.createElement('div');
        errorDiv.className = 'form-error';
        errorDiv.textContent = message;
        const wrapper = commentForm.querySelector('.comment-form-input-wrapper');
        if (wrapper) {
            wrapper.parentNode.insertBefore(errorDiv, wrapper.nextSibling);
        }
    };

    /**
     * Limpia el textarea del comentario
     */
    const clearForm = () => {
        commentInput.value = '';
        if (charCount) {
            charCount.textContent = '0';
        }
        clearErrors();
    };

    /**
     * Inserta un nuevo comentario al principio de la lista
     */
    const insertComment = (html) => {
        const emptyContainer = commentsList.querySelector('.comments-empty');
        if (emptyContainer) {
            emptyContainer.remove();
        }

        const commentElement = document.createElement('div');
        commentElement.innerHTML = html;
        commentsList.insertBefore(commentElement.firstElementChild, commentsList.firstChild);
    };

    /**
     * Obtiene el ID del recurso del formulario
     */
    const getResourceId = () => {
        return commentForm.getAttribute('data-resource-id') || '';
    };

    /**
     * Actualiza el contador de comentarios si existe
     */
    const updateCommentCount = (count) => {
        const resourceId = getResourceId();

        // Si estamos en la vista detalle, buscar el contador en la página actual
        // Si estamos en el feed, buscar el contador del recurso específico
        if (resourceId) {
            // Buscar el elemento data-comments-count del recurso específico
            const commentCountElement = document.querySelector(
                `[data-resource-id="${resourceId}"] [data-comments-count], ` +
                `[data-comments-count][data-resource-id="${resourceId}"]`
            );

            if (commentCountElement) {
                commentCountElement.textContent = count;
                return;
            }
        }

        // Fallback: buscar cualquier contador de comentarios (para compatibilidad)
        const commentIcon = document.querySelector('.icon-comment');
        if (commentIcon) {
            const countSpan = commentIcon.parentElement?.querySelector('[data-comments-count]');
            if (countSpan) {
                countSpan.textContent = count;
            }
        }
    };

    /**
     * Valida el contenido del comentario
     */
    const validateComment = (text) => {
        if (!text || text.trim() === '') {
            return 'El comentario no puede estar vacío.';
        }
        if (text.length > 750) {
            return 'El comentario no puede exceder 750 caracteres.';
        }
        return null;
    };

    /**
     * Envía el comentario por AJAX
     */
    const submitComment = async (e) => {
        e.preventDefault();

        const commentText = commentInput.value;
        const validationError = validateComment(commentText);

        if (validationError) {
            showError(validationError);
            return;
        }

        const storeUrl = getStoreUrl();
        const csrfToken = getCsrfToken();

        if (!storeUrl || !csrfToken) {
            showError('No se pudo procesar el comentario. Intenta recargando la página.');
            return;
        }

        try {
            const response = await fetch(storeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    comment: commentText,
                }),
            });

            if (response.status === 422) {
                const data = await response.json();
                const errors = data.errors?.comment;
                const errorMessage = Array.isArray(errors) ? errors[0] : (errors || 'Error al guardar el comentario');
                showError(errorMessage);
                return;
            }

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success && data.html) {
                insertComment(data.html);
                clearForm();

                if (data.comments_count !== undefined) {
                    updateCommentCount(data.comments_count);
                }
            } else {
                showError(data.message || 'Error al publicar el comentario');
            }
        } catch (error) {
            console.error('Error al enviar comentario:', error);
            showError('No se pudo enviar el comentario. Intenta de nuevo.');
        }
    };

    /**
     * Maneja el cambio de caracteres en el textarea
     */
    const handleInput = () => {
        if (charCount) {
            charCount.textContent = String(commentInput.value.length);
        }
    };

    /**
     * Maneja el focus automático si existe ?focus=comment
     */
    const handleAutoFocus = () => {
        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.get('focus') === 'comment') {
            commentInput.focus();

            const formContainer = commentForm.closest('.comment-form-container');
            if (formContainer) {
                formContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    };

    // Event listeners
    commentForm.addEventListener('submit', submitComment);
    commentInput.addEventListener('input', handleInput);

    // Auto-focus si existe en URL
    handleAutoFocus();
}
