// Toggle favorito en iconos de corazón
document.addEventListener('DOMContentLoaded', function () {
    const heartIcons = document.querySelectorAll('.icon-heart');

    // Path para corazón outline (vacío)
    const outlinePath = "m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z";

    // Path para corazón filled (lleno)
    const filledPath = "m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Z";

    heartIcons.forEach(heart => {
        // Estado inicial: verificar si está marcado como favorito
        let isFavorite = heart.getAttribute('data-favorite') === 'true';
        const path = heart.querySelector('path');

        // Establecer estado inicial
        if (isFavorite) {
            path.setAttribute('d', filledPath);
        }

        heart.addEventListener('click', function () {
            if (isFavorite) {
                // Desmarcar favorito - cambiar a outline
                path.setAttribute('d', outlinePath);
                this.setAttribute('data-favorite', 'false');
                isFavorite = false;
            } else {
                // Marcar favorito - cambiar a filled morado oscuro
                path.setAttribute('d', filledPath);
                this.setAttribute('data-favorite', 'true');
                isFavorite = true;
            }
        });
    });

    // Toggle bookmark
    const bookmarkIcons = document.querySelectorAll('.icon-bookmark');

    bookmarkIcons.forEach(bookmark => {
        bookmark.addEventListener('click', function () {
            const isSaved = this.getAttribute('data-saved') === 'true';

            if (isSaved) {
                this.setAttribute('data-saved', 'false');
                this.setAttribute('fill', '#2d1b3d');
            } else {
                this.setAttribute('data-saved', 'true');
                this.setAttribute('fill', '#583473');
            }
        });
    });

    const tabContainer = document.querySelector('.forYou-follow-section');
    const forYouTab = document.querySelector('.k-forYou');
    const followTab = document.querySelector('.k-follow');

    if (tabContainer && forYouTab && followTab) {
        const setActiveTab = (tab) => {
            forYouTab.classList.remove('tab-active');
            followTab.classList.remove('tab-active');

            if (tab === 'follow') {
                followTab.classList.add('tab-active');
                tabContainer.classList.add('is-follow');
            } else {
                forYouTab.classList.add('tab-active');
                tabContainer.classList.remove('is-follow');
            }
        };

        forYouTab.addEventListener('click', () => setActiveTab('for-you'));
        followTab.addEventListener('click', () => setActiveTab('follow'));
    }

    // Follow button toggle
    const followButtons = document.querySelectorAll('.follow-btn, .teacher-follow-btn');

    followButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const span = this.querySelector('span');
            const isFollowing = span.textContent === 'Siguiendo';

            if (isFollowing) {
                span.textContent = 'Seguir';
                this.classList.remove('is-following');
            } else {
                span.textContent = 'Siguiendo';
                this.classList.add('is-following');
            }
        });
    });

    // Mobile menu toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const mobileOverlay = document.querySelector('.mobile-overlay');
    const appLeft = document.querySelector('.app-left');

    if (mobileMenuBtn && mobileOverlay && appLeft) {
        const toggleMenu = () => {
            mobileMenuBtn.classList.toggle('active');
            mobileOverlay.classList.toggle('active');
            appLeft.classList.toggle('menu-open');
            
            // Prevent body scroll when menu is open
            if (appLeft.classList.contains('menu-open')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        };

        mobileMenuBtn.addEventListener('click', toggleMenu);
        mobileOverlay.addEventListener('click', toggleMenu);
    }
});

document.addEventListener('DOMContentLoaded', () => {
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
});