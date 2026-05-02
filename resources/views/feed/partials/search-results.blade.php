<section class="feed-search-results">
    <div class="feed-search-header">
        <span>Resultados de búsqueda</span>
        <h2>{{ $searchQuery }}</h2>
    </div>

    @if ($users->isNotEmpty())
        <div class="feed-search-section">
            <h3>Perfiles</h3>

            <div class="feed-search-users">
                @foreach ($users as $user)
                    @include('feed.partials.search-user-card', ['user' => $user])
                @endforeach
            </div>
        </div>
    @endif

    <div class="feed-search-section">
        <h3>Recursos</h3>

        @forelse ($resources as $resource)
            @include('feed.partials.resource-card', ['resource' => $resource])
        @empty
            <div class="feed-search-empty">
                No se han encontrado recursos para esta búsqueda.
            </div>
        @endforelse
    </div>

    @if ($users->isEmpty() && $resources->isEmpty())
        <div class="feed-search-empty">
            No se han encontrado resultados.
        </div>
    @endif
</section>