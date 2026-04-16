<header class="k-header">
    <div class="k-header-inner">
        <button class="mobile-menu-btn" aria-label="Abrir menú">
            <span></span>
            <span></span>
            <span></span>
        </button>
        
        <a class="k-brand" href="{{ route('home') }}">
            <img class="k-logo" src="/assets/img/k-logo.png" alt="Logo de klassify">
            <span class="k-name">KLASSIFY</span>
        </a>

        <div class="k-search">
            <!--Afegir recurs-->
            <a href="{{ route('resources.create') }}" class="add-button">
                <span class="button__text">Añadir</span>
                <span class="button__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" viewBox="0 0 24 24" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" stroke="currentColor" height="24" fill="none" class="svg">
                        <line y2="19" y1="5" x2="12" x1="12"></line>
                        <line y2="12" y1="12" x2="19" x1="5"></line>
                    </svg></span>
            </a>
            <form class="form">
                <button>
                    <svg width="17" height="16" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="search">
                        <path d="M7.667 12.667A5.333 5.333 0 107.667 2a5.333 5.333 0 000 10.667zM14.334 14l-2.9-2.9" stroke="currentColor" stroke-width="1.333" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
                <input class="input" placeholder="Buscar" required="" type="text">
                <button class="reset" type="reset">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </form>
        </div>

        <div class="k-header-actions">
            <!--Calendario-->
            <button class="calendar-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" viewBox="0 0 24 24" height="30" fill="none" class="svg-icon">
                    <g stroke-width="2" stroke-linecap="round" stroke="#583473">
                        <rect y="5" x="4" width="16" rx="2" height="16"></rect>
                        <path d="m8 3v4"></path>
                        <path d="m16 3v4"></path>
                        <path d="m4 11h16"></path>
                    </g>
                </svg>
            </button>
            <!--Notificaciones-->
            <button class="notifications-button">
                <svg viewBox="0 0 448 512" class="bell">
                    <path d="M224 0c-17.7 0-32 14.3-32 32V49.9C119.5 61.4 64 124.2 64 200v33.4c0 45.4-15.5 89.5-43.8 124.9L5.3 377c-5.8 7.2-6.9 17.1-2.9 25.4S14.8 416 24 416H424c9.2 0 17.6-5.3 21.6-13.6s2.9-18.2-2.9-25.4l-14.9-18.6C399.5 322.9 384 278.8 384 233.4V200c0-75.8-55.5-138.6-128-150.1V32c0-17.7-14.3-32-32-32zm0 96h8c57.4 0 104 46.6 104 104v33.4c0 47.9 13.9 94.6 39.7 134.6H72.3C98.1 328 112 281.3 112 233.4V200c0-57.4 46.6-104 104-104h8zm64 352H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7s18.7-28.3 18.7-45.3z"></path>
                </svg>
            </button>
            <!--Perfil-->
            <div class="k-profile" tabindex="0">
                <button class="k-profile-btn" type="button" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ asset('assets/img/default-profile-img.png') }}" alt="Foto de perfil" class="k-profile-icon">
                    <span class="k-profile-name">{{ auth()->user()->name ?? 'Usuario' }}</span>
                    <span class="k-profile-chev">▾</span>
                </button>

                <div class="k-profile-menu" role="menu" aria-label="Perfil">
                    <a class="k-profile-item" href="/perfil" role="menuitem">Perfil</a>
                        @if (auth()->check() && strtoupper((string) auth()->user()->role) === 'ADMIN')
                        <a class="k-profile-item" href="{{ route('admin.teacher-requests.index') }}" role="menuitem">Solicitudes</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="k-profile-item" type="submit" role="menuitem">Cerrar sesión</button>
                    </form>
                </div>
            </div>
        </div>


    </div>
</header>