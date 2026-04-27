/**
 * Inicializa la funcionalidad de favoritos (corazones) y guardados (bookmarks)
 */
export function initFavorites() {
    const outlinePath = "m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z";
    const filledPath = "m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Z";

    const setHeartState = (heart, isFavorite) => {
        const path = heart.querySelector('path');

        if (!path) {
            return;
        }

        path.setAttribute('d', isFavorite ? filledPath : outlinePath);
        heart.setAttribute('data-favorite', isFavorite ? 'true' : 'false');
    };

    // Inicializar estado de todos los corazones
    document.querySelectorAll('.icon-heart').forEach((heart) => {
        setHeartState(heart, heart.getAttribute('data-favorite') === 'true');
    });

    // Event delegation para clicks
    document.addEventListener('click', (event) => {
        const heart = event.target.closest('.icon-heart');

        if (heart) {
            const isFavorite = heart.getAttribute('data-favorite') === 'true';
            setHeartState(heart, !isFavorite);
            return;
        }

        const bookmark = event.target.closest('.icon-bookmark');

        if (!bookmark) {
            return;
        }

        const isSaved = bookmark.getAttribute('data-saved') === 'true';
        bookmark.setAttribute('data-saved', isSaved ? 'false' : 'true');
        bookmark.setAttribute('fill', isSaved ? '#2d1b3d' : '#583473');
    });

    // Botones de seguir
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

    // Tabs de Para ti / Siguiendo
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
}
