function initSidebarLoadMore() {
    if (window.__sidebarLoadMoreInitialized) {
        return;
    }

    window.__sidebarLoadMoreInitialized = true;

    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-sidebar-load-more]');

        if (!button) {
            return;
        }

        event.preventDefault();

        if (button.disabled || button.dataset.loadedOnce === 'true') {
            return;
        }

        const url = button.dataset.loadMoreUrl;
        const targetSelector = button.dataset.loadMoreTarget;
        const target = document.querySelector(targetSelector);

        if (!url || !target) {
            return;
        }

        button.disabled = true;
        button.textContent = 'Cargando...';

        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error('No se pudo cargar más contenido.');
            }

            if (data.html) {
                target.insertAdjacentHTML('beforeend', data.html);
            }

            button.dataset.loadedOnce = 'true';
            button.remove();
        } catch (error) {
            console.error(error);
            button.disabled = false;
            button.textContent = 'Mostrar más';
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSidebarLoadMore, { once: true });
} else {
    initSidebarLoadMore();
}