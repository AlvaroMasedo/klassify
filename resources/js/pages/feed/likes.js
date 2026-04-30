export function initLikes() {
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content');

    const updateLikeButtons = (resourceId, isLiked) => {
        document
            .querySelectorAll(`[data-like-toggle][data-resource-id="${resourceId}"]`)
            .forEach((button) => {
                button.classList.toggle('is-liked', isLiked);
                button.setAttribute('aria-pressed', isLiked ? 'true' : 'false');
                button.setAttribute('aria-label', isLiked ? 'Quitar like' : 'Dar like');
            });
    };

    const updateLikeCounts = (resourceId, count) => {
        document
            .querySelectorAll(`[data-likes-count-for="${resourceId}"]`)
            .forEach((counter) => {
                counter.textContent = count;
            });
    };

    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-like-toggle]');

        if (!button) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        if (!csrfToken) {
            console.error('No se encontró el token CSRF.');
            return;
        }

        const url = button.dataset.likeUrl;
        const resourceId = button.dataset.resourceId;

        if (!url || !resourceId || button.disabled) {
            return;
        }

        button.disabled = true;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error('No se pudo actualizar el like.');
            }

            updateLikeButtons(resourceId, Boolean(data.is_liked));
            updateLikeCounts(resourceId, data.likes_count ?? 0);
        } catch (error) {
            console.error(error);
        } finally {
            button.disabled = false;
        }
    });
}