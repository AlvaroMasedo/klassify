/**
 * Inicializa la funcionalidad de cargar más recursos (paginación)
 */
export function initLoadMore() {
    const appCenter = document.querySelector('.app-center');
    const loadMoreContainer = document.querySelector('.feed-load-more');
    const loadMoreBtn = document.querySelector('[data-feed-load-more]');

    if (!appCenter || !loadMoreContainer || !loadMoreBtn) {
        return;
    }

    let isLoadingMore = false;

    loadMoreBtn.addEventListener('click', async (event) => {
        event.preventDefault();

        if (isLoadingMore) {
            return;
        }

        const nextUrl = loadMoreBtn.getAttribute('href');

        if (!nextUrl) {
            return;
        }

        isLoadingMore = true;
        loadMoreBtn.classList.add('is-loading');
        loadMoreBtn.textContent = 'Cargando recursos...';

        try {
            const response = await fetch(nextUrl, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('No se pudo cargar la siguiente página de recursos.');
            }

            const data = await response.json();
            
            // Crear un contenedor temporal para parsear el HTML
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.html;
            
            // Obtener todas las tarjetas de recurso del HTML
            const newCards = tempDiv.querySelectorAll('.recurs-card');

            // Insertar cada tarjeta antes del contenedor de "Carga más"
            newCards.forEach((card) => {
                appCenter.insertBefore(card, loadMoreContainer);

                // Reinicializar componentes para nuevas tarjetas
                if (window.__feedAudioPlayerUtils) {
                    window.__feedAudioPlayerUtils.initPreviewThumbs(card);
                    window.__feedAudioPlayerUtils.observeAudioPlayers(card);
                }
            });

            // Actualizar la URL del botón para la siguiente página
            if (data.next_page_url) {
                loadMoreBtn.setAttribute('href', data.next_page_url);
            } else {
                // Si no hay más páginas, ocultar el contenedor del botón
                loadMoreContainer.remove();
            }
        } catch (error) {
            console.error('Error loading more resources:', error);
                loadMoreBtn.textContent = 'Error al cargar. Inténtalo de nuevo';
        } finally {
            if (document.body.contains(loadMoreBtn)) {
                loadMoreBtn.classList.remove('is-loading');

                if (loadMoreBtn.textContent !== 'Error al cargar. Inténtalo de nuevo') {
                    loadMoreBtn.textContent = 'Carga más recursos';
                }
            }

            isLoadingMore = false;
        }
    });
}

