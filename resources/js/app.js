import './bootstrap';

// Carga dinámica de JavaScript específico de página
const pageName = document.body.getAttribute('data-page');

if (pageName) {
    switch (pageName) {
        case 'feed':
            import('./pages/feed/index.js');
            break;
        case 'profile':
            import('./pages/profile/index.js');
            break;
        case 'register':
            import('./pages/register.js');
            break;
    }
}