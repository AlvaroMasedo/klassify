export function initLoadMore() {
    if (window.__feedLoadMoreInitialized) {
        return;
    }

    window.__feedLoadMoreInitialized = true;

    const reinitDynamicContent = (scope) => {
        if (window.__feedAudioPlayerUtils) {
            window.__feedAudioPlayerUtils.initPreviewThumbs(scope);
            window.__feedAudioPlayerUtils.observeAudioPlayers(scope);
        }
    };

    document.addEventListener('click', async (event) => {
        const loadMoreBtn = event.target.closest('[data-feed-load-more]');

        if (!loadMoreBtn) {
            return;
        }

        event.preventDefault();

        if (loadMoreBtn.classList.contains('is-loading')) {
            return;
        }

        const resultsContainer = document.querySelector('[data-feed-results]');
        const loadMoreArea = document.querySelector('[data-feed-load-more-area]');
        const nextUrl = loadMoreBtn.getAttribute('href');

        if (!resultsContainer || !nextUrl) {
            return;
        }

        loadMoreBtn.classList.add('is-loading');
        loadMoreBtn.textContent = 'Cargando recursos...';

        try {
            const response = await fetch(nextUrl, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error('No se pudo cargar la siguiente página de recursos.');
            }

            const data = await response.json();

            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.html || '';

            const newCards = tempDiv.querySelectorAll('.recurs-card');

            newCards.forEach((card) => {
                resultsContainer.appendChild(card);
            });

            reinitDynamicContent(resultsContainer);

            if (loadMoreArea) {
                loadMoreArea.innerHTML = data.load_more_html || '';

                if (!data.has_more) {
                    loadMoreArea.innerHTML = '';
                }
            }
        } catch (error) {
            console.error('Error loading more resources:', error);

            loadMoreBtn.classList.remove('is-loading');
            loadMoreBtn.textContent = 'Error al cargar. Inténtalo de nuevo';
        }
    });
}