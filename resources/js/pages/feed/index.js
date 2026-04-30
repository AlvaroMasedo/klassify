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

/**
 * Inicializa los filtros de curso y materia
 */
function initFilters() {
    const courseSelect = document.getElementById('course-filter');
    const subjectSelect = document.getElementById('subject-filter');

    if (!courseSelect || !subjectSelect) {
        return;
    }

    const availableCourses = Array.isArray(window.coursesWithSubjects)
        ? window.coursesWithSubjects
        : [];

    function resetSubjects() {
        subjectSelect.innerHTML = '<option value="" selected disabled>Materia</option>';
        subjectSelect.disabled = true;
    }

    function loadSubjects(courseId) {
        const selectedCourse = availableCourses.find(course => course.id == courseId);

        subjectSelect.innerHTML = '<option value="" selected disabled>Materia</option>';

        if (!selectedCourse || !selectedCourse.subjects || selectedCourse.subjects.length === 0) {
            subjectSelect.disabled = true;
            return;
        }

        selectedCourse.subjects.forEach(subject => {
            const option = document.createElement('option');
            option.value = subject.id;
            option.textContent = subject.name;
            subjectSelect.appendChild(option);
        });

        subjectSelect.disabled = false;
    }

    resetSubjects();

    courseSelect.addEventListener('change', () => {
        const courseId = courseSelect.value;

        if (!courseId) {
            resetSubjects();
            return;
        }

        loadSubjects(courseId);
    });
}

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
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initFeedPage, { once: true });
} else {
    initFeedPage();
}
