function initReports() {
    const modal = document.querySelector('[data-report-modal]');

    if (!modal || window.__reportsInitialized) {
        return;
    }

    window.__reportsInitialized = true;

    const form = modal.querySelector('[data-report-form]');
    const textarea = modal.querySelector('textarea[name="reason"]');
    const message = modal.querySelector('[data-report-message]');
    const description = modal.querySelector('[data-report-description]');
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content');

    let currentUrl = null;

    const openModal = (button) => {
        currentUrl = button.dataset.reportUrl || null;

        const type = button.dataset.reportType === 'comment' ? 'comentario' : 'recurso';
        const title = button.dataset.reportTitle || type;

        description.textContent = `Vas a denunciar este ${type}: ${title}. Explica el motivo de la denuncia.`;

        textarea.value = '';
        message.textContent = '';
        message.classList.remove('is-success', 'is-error');

        modal.hidden = false;
        document.body.classList.add('report-modal-open');

        setTimeout(() => textarea.focus(), 50);
    };

    const closeModal = () => {
        modal.hidden = true;
        document.body.classList.remove('report-modal-open');
        currentUrl = null;
    };

    document.addEventListener('click', (event) => {
        const openButton = event.target.closest('[data-report-open]');

        if (openButton) {
            event.preventDefault();
            event.stopPropagation();
            openModal(openButton);
            return;
        }

        if (event.target.closest('[data-report-close]')) {
            event.preventDefault();
            closeModal();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.hidden) {
            closeModal();
        }
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (!currentUrl || !csrfToken) {
            return;
        }

        const submitButton = form.querySelector('button[type="submit"]');

        message.textContent = '';
        message.classList.remove('is-success', 'is-error');
        submitButton.disabled = true;

        try {
            const formData = new FormData(form);

            const response = await fetch(currentUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'No se pudo enviar la denuncia.');
            }

            message.textContent = data.message || 'Reporte completado correctamente.';
            message.classList.add('is-success');

            setTimeout(closeModal, 1200);
        } catch (error) {
            message.textContent = error.message || 'No se pudo enviar la denuncia.';
            message.classList.add('is-error');
        } finally {
            submitButton.disabled = false;
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initReports, { once: true });
} else {
    initReports();
}