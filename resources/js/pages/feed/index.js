/**
 * Feed page entry point - importa y ejecuta todos los módulos
 */
import { initFavorites } from './favorites.js';
import { initMobileMenu } from './mobileMenu.js';
import { initResourceMenus } from './resourceMenus.js';
import { initPreviewModal } from './previewModal.js';
import { initAudioPlayers } from './audioPlayer.js';
import { initLoadMore } from './loadMore.js';
import { initResourceDetailNavigation } from './resourceDetailNavigation.js';
import { initCommentNavigation } from './commentNavigation.js';
import { initComments } from './comments.js';
import { initLikes } from './likes.js';
import { initSuggestedTeacherFollows } from './suggestedTeachers.js';
import { initFeedSearch } from './search.js';
import { initFilters } from './filters.js';

/**
 * Inicializa todos los módulos de la página feed
 */
function initFeedPage() {
    initFavorites();
    initLikes();
    initMobileMenu();
    initResourceMenus();
    initPreviewModal();
    initAudioPlayers();
    initLoadMore();
    initResourceDetailNavigation();
    initCommentNavigation();
    initComments();
    initFilters();
    initSuggestedTeacherFollows();
    initFeedSearch();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initFeedPage, { once: true });
} else {
    initFeedPage();
}
