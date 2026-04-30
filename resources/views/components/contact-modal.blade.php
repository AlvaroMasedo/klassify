<div class="contact-modal" hidden data-contact-modal>
    <div class="contact-modal__backdrop" data-contact-close></div>

    <section class="contact-modal__card" role="dialog" aria-modal="true" aria-labelledby="contact-modal-title">
        <button type="button" class="contact-modal__close" data-contact-close aria-label="Cerrar">
            ×
        </button>

        <span class="contact-modal__kicker">Contacto</span>

        <h2 id="contact-modal-title">Contacta con Klassify</h2>

        <p>
            Si tienes un problema con la plataforma, lo mejor es enviar una incidencia desde el Centro de ayuda.
            Así un administrador podrá revisarla.
        </p>

        <div class="contact-modal__info">
            <div>
                <strong>Soporte</strong>
                <span>Usa el formulario de incidencias para problemas técnicos o de usuario.</span>
            </div>

            <div>
                <strong>Comunidad</strong>
                <span>También puedes denunciar recursos o comentarios desde los tres puntitos.</span>
            </div>
        </div>

        <div class="contact-modal__actions">
            @auth
                <a href="{{ route('incidents.create') }}" class="contact-modal__primary">
                    Enviar incidencia
                </a>
            @else
                <a href="{{ route('login') }}" class="contact-modal__primary">
                    Iniciar sesión
                </a>
            @endauth

            <button type="button" class="contact-modal__secondary" data-contact-close>
                Cerrar
            </button>
        </div>
    </section>
</div>