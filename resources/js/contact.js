function initContactModal() {
    if (window.__contactModalInitialized) {
        return;
    }

    const modal = document.querySelector('[data-contact-modal]');

    if (!modal) {
        return;
    }

    window.__contactModalInitialized = true;

    const openModal = () => {
        modal.hidden = false;
        document.body.classList.add('contact-modal-open');
    };

    const closeModal = () => {
        modal.hidden = true;
        document.body.classList.remove('contact-modal-open');
    };

    document.addEventListener('click', (event) => {
        const openButton = event.target.closest('[data-contact-open]');

        if (openButton) {
            event.preventDefault();
            openModal();
            return;
        }

        if (event.target.closest('[data-contact-close]')) {
            event.preventDefault();
            closeModal();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.hidden) {
            closeModal();
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initContactModal, { once: true });
} else {
    initContactModal();
}