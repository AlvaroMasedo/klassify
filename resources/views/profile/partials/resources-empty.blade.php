<div class="profile-empty">
    <h3>No hay recursos con estos filtros</h3>
    <p>
        @if (($activeTab ?? 'resources') === 'favorites')
            No se han encontrado favoritos que coincidan con los filtros seleccionados.
        @else
            No se han encontrado recursos que coincidan con los filtros seleccionados.
        @endif
    </p>
</div>