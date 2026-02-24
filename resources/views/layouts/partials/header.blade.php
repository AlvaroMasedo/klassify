<header class="k-header">
    <div class="k-header__inner">
        <a class="k-brand" href="{{ route('home') }}">
            <span class="k-brand__logo">K</span>
            <span class="k-brand__name">klassify</span>
        </a>

        <div class="k-search">
            <input class="k-search__input" type="search" placeholder="Buscar recursos..." aria-label="Buscar recursos">
        </div>

        <div class="k-header__right">
            @guest
                <a class="k-btn k-btn--ghost" href="#">Login</a>
                <a class="k-btn k-btn--primary" href="#">Registro</a>
            @endguest

            @auth
                <a class="k-btn k-btn--primary" href="#">+ Añadir recurso</a>
                <a class="k-btn k-btn--ghost" href="#">Perfil</a>
            @endauth
        </div>
    </div>
</header>