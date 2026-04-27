/**
 * Inicializa los menús de opciones de recursos (editar, eliminar)
 */
export function initResourceMenus() {
    const closeResourceMenus = () => {
        document.querySelectorAll('[data-resource-menu-panel]:not([hidden])').forEach((panel) => {
            panel.hidden = true;

            const container = panel.closest('.recurs-more-container');

            if (!container) {
                return;
            }

            const toggle = container.querySelector('[data-resource-menu-toggle]');

            if (toggle) {
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    };

    const toggleResourceMenu = (toggle) => {
        const container = toggle.closest('.recurs-more-container');
        const panel = container ? container.querySelector('[data-resource-menu-panel]') : null;

        if (!panel) {
            return;
        }

        const shouldOpen = panel.hidden;
        closeResourceMenus();
        panel.hidden = !shouldOpen ? true : false;
        toggle.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');
    };

    // Event delegation para clicks en menús de recurso y eliminar
    document.addEventListener('click', (event) => {
        const menuToggle = event.target.closest('[data-resource-menu-toggle]');

        if (menuToggle) {
            event.preventDefault();
            event.stopPropagation();
            toggleResourceMenu(menuToggle);
            return;
        }

        const deleteButton = event.target.closest('[data-resource-delete-form] button[type="submit"]');

        if (deleteButton) {
            const confirmed = window.confirm('¿Seguro que quieres eliminar este recurso?');

            if (!confirmed) {
                event.preventDefault();
            }

            return;
        }

        // Cerrar menús cuando se hace click fuera
        if (!event.target.closest('.recurs-more-container')) {
            closeResourceMenus();
        }
    });

    // Cerrar menús con ESC
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeResourceMenus();
        }
    });
}
