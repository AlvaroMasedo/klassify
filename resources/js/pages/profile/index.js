function initProfileFollows() {
    if (window.__profileFollowsInitialized) {
        return;
    }

    window.__profileFollowsInitialized = true;

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content');

    const formatFollowersText = (count) => {
        return `${count} ${count === 1 ? 'Seguidor' : 'Seguidores'}`;
    };

    const updateFollowButtons = (userId, isFollowing) => {
        document
            .querySelectorAll(`[data-follow-toggle][data-user-id="${userId}"]`)
            .forEach((button) => {
                button.classList.toggle('is-following', isFollowing);
                button.textContent = isFollowing ? 'Siguiendo' : 'Seguir';
                button.setAttribute('aria-pressed', isFollowing ? 'true' : 'false');
            });
    };

    const updateFollowersCount = (userId, count) => {
        document
            .querySelectorAll(`[data-followers-count-for="${userId}"]`)
            .forEach((counter) => {
                counter.textContent = formatFollowersText(Number(count || 0));
            });
    };

    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-follow-toggle]');

        if (!button) {
            return;
        }

        event.preventDefault();

        if (!csrfToken || button.disabled) {
            return;
        }

        const url = button.dataset.followUrl;
        const userId = button.dataset.userId;

        if (!url || !userId) {
            return;
        }

        button.disabled = true;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'No se pudo actualizar el seguimiento.');
            }

            updateFollowButtons(userId, Boolean(data.is_following));
            updateFollowersCount(userId, data.followers_count ?? 0);
        } catch (error) {
            console.error(error);
        } finally {
            button.disabled = false;
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initProfileFollows, { once: true });
} else {
    initProfileFollows();
}