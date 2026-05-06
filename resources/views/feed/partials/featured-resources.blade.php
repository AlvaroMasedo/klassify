<div data-featured-resources-list>
    @forelse ($featuredResources as $resource)
        @include('feed.partials.featured-resource-item', ['resource' => $resource])
    @empty
        <div class="rd-card">
            <h3>No hay recursos destacados</h3>
            <p class="p-pequeño">Cuando haya publicaciones reales, aparecerán aquí.</p>
        </div>
    @endforelse
</div>

@if ($featuredResourcesHasMore ?? false)
    <button
        type="button"
        class="view-more sidebar-load-more-btn"
        data-sidebar-load-more
        data-load-more-target="[data-featured-resources-list]"
        data-load-more-url="{{ route('feed.featured-resources.more') }}">
        Mostrar más
    </button>
@endif