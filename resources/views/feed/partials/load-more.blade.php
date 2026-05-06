@if ($resources->hasMorePages())
<div class="feed-load-more">
    <a href="{{ $resources->nextPageUrl() }}" class="feed-load-more-btn" data-feed-load-more>Carga más recursos</a>
</div>
@endif
