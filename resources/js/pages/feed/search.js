export function initFeedSearch() {
    const input = document.querySelector('[data-live-search]');
    const resultsContainer = document.querySelector('[data-feed-results]');
    const loadMoreArea = document.querySelector('[data-feed-load-more-area]');

    if (!input || !resultsContainer || window.__feedSearchInitialized) {
        return;
    }

    window.__feedSearchInitialized = true;

    const searchUrl = input.dataset.searchUrl;
    const originalResultsHtml = resultsContainer.innerHTML;
    const originalLoadMoreHtml = loadMoreArea ? loadMoreArea.innerHTML : '';

    let debounceTimer = null;
    let abortController = null;

    const setSearchingState = () => {
        resultsContainer.innerHTML = `
            <div class="feed-search-loading">
                Buscando...
            </div>
        `;

        if (loadMoreArea) {
            loadMoreArea.hidden = true;
        }
    };

    const restoreFeed = () => {
        resultsContainer.innerHTML = originalResultsHtml;

        if (loadMoreArea) {
            loadMoreArea.innerHTML = originalLoadMoreHtml;
            loadMoreArea.hidden = false;
        }
    };

    const runSearch = async (query) => {
        const cleanQuery = query.trim();

        if (cleanQuery.length === 0) {
            restoreFeed();
            return;
        }

        if (cleanQuery.length < 2) {
            return;
        }

        if (!searchUrl) {
            return;
        }

        if (abortController) {
            abortController.abort();
        }

        abortController = new AbortController();

        setSearchingState();

        try {
            const url = new URL(searchUrl, window.location.origin);
            url.searchParams.set('q', cleanQuery);

            const response = await fetch(url.toString(), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                },
                signal: abortController.signal,
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error('No se pudo realizar la búsqueda.');
            }

            resultsContainer.innerHTML = data.html || '';

            if (loadMoreArea) {
                loadMoreArea.hidden = true;
            }
        } catch (error) {
            if (error.name === 'AbortError') {
                return;
            }

            console.error(error);

            resultsContainer.innerHTML = `
                <div class="feed-search-empty">
                    No se pudo realizar la búsqueda.
                </div>
            `;
        }
    };

    input.addEventListener('input', () => {
        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(() => {
            runSearch(input.value);
        }, 300);
    });

    input.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            input.value = '';
            restoreFeed();
        }
    });
}