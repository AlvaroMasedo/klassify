export function initSuggestedTeacherFollows() {
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content');

    const updateFollowButtons = (userId, isFollowing) => {
        document
            .querySelectorAll(`[data-follow-toggle][data-user-id="${userId}"]`)
            .forEach((button) => {
                button.classList.toggle('is-following', isFollowing);
                button.setAttribute('aria-pressed', isFollowing ? 'true' : 'false');

                const label = button.querySelector('span');

                if (label) {
                    label.textContent = isFollowing ? 'Siguiendo' : 'Seguir';
                } else {
                    button.textContent = isFollowing ? 'Siguiendo' : 'Seguir';
                }
            });
    };

    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-follow-toggle]');

        if (!button) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

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
        } catch (error) {
            console.error(error);
        } finally {
            button.disabled = false;
        }
    });
}