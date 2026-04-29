export function initFavorites() {
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content');

    const toast = document.getElementById('favorite-toast');
    const toastLink = toast?.querySelector('[data-favorite-toast-link]');
    let toastTimeout = null;

    const showFavoriteToast = (url) => {
        if (!toast || !toastLink) {
            return;
        }

        if (url) {
            toastLink.href = url;
        }

        toast.hidden = false;
        toast.classList.add('is-visible');

        window.clearTimeout(toastTimeout);

        toastTimeout = window.setTimeout(() => {
            toast.classList.remove('is-visible');
            toast.hidden = true;
        }, 5000);
    };

    const updateFavoriteButtons = (resourceId, isFavorited) => {
        document
            .querySelectorAll(`[data-favorite-toggle][data-resource-id="${resourceId}"]`)
            .forEach((button) => {
                button.classList.toggle('is-favorited', isFavorited);
                button.setAttribute('aria-pressed', isFavorited ? 'true' : 'false');
                button.setAttribute(
                    'aria-label',
                    isFavorited ? 'Quitar de favoritos' : 'Guardar en favoritos'
                );
            });
    };

    const updateFavoriteCounts = (resourceId, count) => {
        document
            .querySelectorAll(`[data-favorites-count-for="${resourceId}"]`)
            .forEach((counter) => {
                counter.textContent = count;
            });
    };

    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-favorite-toggle]');

        if (!button) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        if (!csrfToken) {
            console.error('No se encontró el token CSRF.');
            return;
        }

        const url = button.dataset.favoriteUrl;
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
                throw new Error(data.message || 'No se pudo actualizar favoritos.');
            }

            updateFavoriteButtons(resourceId, Boolean(data.is_favorited));
            updateFavoriteCounts(resourceId, data.favorites_count ?? 0);

            if (data.is_favorited) {
                showFavoriteToast(data.favorites_url);
            }
        } catch (error) {
            console.error(error);
        } finally {
            button.disabled = false;
        }
    });
}