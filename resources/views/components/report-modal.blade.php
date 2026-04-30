<div class="report-modal" hidden data-report-modal>
    <div class="report-modal__backdrop" data-report-close></div>

    <div class="report-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="report-modal-title">
        <button type="button" class="report-modal__close" data-report-close aria-label="Cerrar">
            ×
        </button>

        <h2 id="report-modal-title">Denunciar contenido</h2>

        <p class="report-modal__text" data-report-description>
            Explica el motivo de la denuncia.
        </p>

        <form data-report-form>
            <textarea
                name="reason"
                class="report-modal__textarea"
                rows="5"
                minlength="5"
                maxlength="1000"
                required
                placeholder="Escribe el motivo de la denuncia..."></textarea>

            <p class="report-modal__message" data-report-message></p>

            <div class="report-modal__actions">
                <button type="button" class="report-modal__cancel" data-report-close>
                    Cancelar
                </button>

                <button type="submit" class="report-modal__submit">
                    Enviar denuncia
                </button>
            </div>
        </form>
    </div>
</div>