/**
 * Inicializa el toggle del menú móvil
 */
export function initMobileMenu() {
    // Guard: Evitar inicialización múltiple
    if (window.__feedMobileMenuInitialized) {
        return;
    }
    window.__feedMobileMenuInitialized = true;

    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const mobileOverlay = document.querySelector('.mobile-overlay');
    const appLeft = document.querySelector('.app-left');

    if (!mobileMenuBtn || !mobileOverlay || !appLeft) {
        return;
    }

    const toggleMenu = () => {
        mobileMenuBtn.classList.toggle('active');
        mobileOverlay.classList.toggle('active');
        appLeft.classList.toggle('menu-open');

        // Prevenir scroll del body cuando el menú está abierto
        if (appLeft.classList.contains('menu-open')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    };

    mobileMenuBtn.addEventListener('click', toggleMenu);
    mobileOverlay.addEventListener('click', toggleMenu);
}
