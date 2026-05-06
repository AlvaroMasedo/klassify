@if ($resources->hasPages())
    <nav class="profile-pagination-simple" aria-label="Paginacion de recursos">
        <a
            href="{{ $resources->previousPageUrl() ?: '#' }}"
            class="profile-page-btn {{ $resources->onFirstPage() ? 'is-disabled' : '' }}"
            aria-disabled="{{ $resources->onFirstPage() ? 'true' : 'false' }}">
            Anterior
        </a>

        <div class="profile-page-numbers">
            @foreach ($resources->getUrlRange(1, $resources->lastPage()) as $page => $url)
                <a
                    href="{{ $url }}"
                    class="profile-page-number {{ $resources->currentPage() === $page ? 'is-active' : '' }}"
                    aria-current="{{ $resources->currentPage() === $page ? 'page' : 'false' }}">
                    {{ $page }}
                </a>
            @endforeach
        </div>

        <a
            href="{{ $resources->nextPageUrl() ?: '#' }}"
            class="profile-page-btn {{ $resources->hasMorePages() ? '' : 'is-disabled' }}"
            aria-disabled="{{ $resources->hasMorePages() ? 'false' : 'true' }}">
            Siguiente
        </a>
    </nav>
@endif