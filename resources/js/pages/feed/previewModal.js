/**
 * Inicializa el modal de preview de recursos
 */
export function initPreviewModal() {
    // Guard: Evitar inicialización múltiple
    if (window.__feedPreviewModalInitialized) {
        return;
    }
    window.__feedPreviewModalInitialized = true;

    const previewModal = document.getElementById('resource-preview-modal');
    const previewStage = document.getElementById('resource-preview-stage');
    const previewTitle = document.getElementById('resource-preview-title');

    if (!previewModal || !previewStage || !previewTitle) {
        return;
    }

    previewModal.hidden = true;

    const closePreview = () => {
        previewModal.hidden = true;
        previewStage.innerHTML = '';
        previewTitle.textContent = '';
        document.body.classList.remove('menu-open');
    };

    const openPreview = (url, kind, title) => {
        previewStage.innerHTML = '';
        previewTitle.textContent = title || 'Vista previa';

        if (kind === 'video') {
            const video = document.createElement('video');
            video.controls = true;
            video.preload = 'metadata';
            video.src = url;
            previewStage.appendChild(video);
        } else if (kind === 'image') {
            const image = document.createElement('img');
            image.src = url;
            image.alt = title || 'Vista previa del recurso';
            previewStage.appendChild(image);
        } else if (kind === 'pdf') {
            const docWrap = document.createElement('div');
            docWrap.className = 'resource-preview-doc-wrap';

            const iframe = document.createElement('iframe');
            iframe.className = 'resource-preview-doc-frame';
            iframe.setAttribute('title', title || 'Vista previa del documento');
            iframe.setAttribute('scrolling', 'no');
            iframe.src = `${url}#page=1`;

            docWrap.appendChild(iframe);
            previewStage.appendChild(docWrap);
        } else {
            const fallback = document.createElement('div');
            fallback.className = 'resource-preview-doc-unavailable';

            const text = document.createElement('p');
            text.textContent = 'No hay vista previa integrada para este tipo de archivo.';

            const link = document.createElement('a');
            link.href = url;
            link.target = '_blank';
            link.rel = 'noreferrer';
            link.textContent = 'Abrir documento';

            fallback.appendChild(text);
            fallback.appendChild(link);
            previewStage.appendChild(fallback);
        }

        previewModal.hidden = false;
        document.body.classList.add('menu-open');
    };

    // Event delegation: Click en trigger de preview
    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('.resource-preview-trigger');

        if (trigger) {
            const url = trigger.getAttribute('data-preview-url') || '';
            const kind = trigger.getAttribute('data-preview-kind') || 'image';
            const title = trigger.getAttribute('data-preview-title') || 'Vista previa';

            if (url) {
                openPreview(url, kind, title);
            }
            return;
        }

        // Event delegation: Botones de cerrar preview
        const closeBtn = event.target.closest('[data-preview-close="true"]');
        if (closeBtn) {
            event.preventDefault();
            closePreview();
        }
    });

    // Cerrar preview con ESC (delegado en document)
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !previewModal.hidden) {
            closePreview();
        }
    });
}
