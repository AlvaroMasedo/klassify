/**
 * Navegación al detalle del recurso desde la tarjeta del archivo.
 */
export function initResourceDetailNavigation() {
    if (window.__feedResourceDetailNavigationInitialized) {
        return;
    }
    window.__feedResourceDetailNavigationInitialized = true;

    const getDetailUrl = (element) => element?.getAttribute('data-resource-show-url') || '';

    const isInteractiveElement = (element) => {
        return Boolean(element.closest('a, button, input, select, textarea, video, audio, [contenteditable="true"]'));
    };

    document.addEventListener('click', (event) => {
        const card = event.target.closest('.recurs-detail-clickable[data-resource-show-url]');

        if (!card) {
            return;
        }

        if (isInteractiveElement(event.target)) {
            return;
        }

        const detailUrl = getDetailUrl(card);

        if (detailUrl) {
            window.location.href = detailUrl;
        }
    });

    document.addEventListener('keydown', (event) => {
        const card = event.target.closest('.recurs-detail-clickable[data-resource-show-url]');

        if (!card) {
            return;
        }

        if (event.key !== 'Enter' && event.key !== ' ') {
            return;
        }

        event.preventDefault();

        const detailUrl = getDetailUrl(card);

        if (detailUrl) {
            window.location.href = detailUrl;
        }
    });
}
