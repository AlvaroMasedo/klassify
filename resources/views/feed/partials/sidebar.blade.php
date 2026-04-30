<div class="k-recursosDestacados-card">
    <h2>Profesores Sugeridos</h2>

    <div data-suggested-teachers-list>
        @forelse (($suggestedTeachers ?? collect()) as $teacher)
            @include('feed.partials.suggested-teacher-item', ['teacher' => $teacher])
        @empty
            <p class="teacher-empty">No hay profesores sugeridos todavía.</p>
        @endforelse
    </div>

    @if ($suggestedTeachersHasMore ?? false)
        <button
            type="button"
            class="view-more sidebar-load-more-btn"
            data-sidebar-load-more
            data-load-more-target="[data-suggested-teachers-list]"
            data-load-more-url="{{ route('feed.suggested-teachers.more') }}">
            Mostrar más
        </button>
    @endif
</div>
</div>

<div class="sidebar-footer">
    <nav class="sidebar-footer-links">
        <a href="{{ route('pages.about') }}">Sobre Klassify</a>
        <a href="{{ route('pages.community') }}">Normas de la Comunidad</a>
        <a href="{{ route('pages.privacy') }}">Privacidad</a>
        <a href="{{ route('incidents.create') }}">Ayuda</a>
        <a href="#" data-contact-open>Contacto</a>
    </nav>

    <div class="sidebar-footer-brand">
        <img src="{{ asset('assets/img/k-logo.png') }}" alt="Logo de Klassify">
        <span>© {{ date('Y') }} Klassify</span>
    </div>
</div>