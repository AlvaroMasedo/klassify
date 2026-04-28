/**
 * Navegación a detalle del recurso con focus en comentarios
 */
export function initCommentNavigation() {
    if (window.__feedCommentNavigationInitialized) {
        return;
    }
    window.__feedCommentNavigationInitialized = true;

    document.addEventListener('click', (event) => {
        const commentButton = event.target.closest('[data-comment-show-url]');

        if (!commentButton) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        const url = commentButton.getAttribute('data-comment-show-url');

        if (url) {
            window.location.href = url;
        }
    });
}
