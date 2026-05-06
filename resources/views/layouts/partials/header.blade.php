<header class="k-header">
    @php
    $profileUser = auth()->user();
    $displayName = trim(((string) ($profileUser?->name ?? '')) . ' ' . ((string) ($profileUser?->surname ?? '')));
    $displayName = $displayName !== '' ? $displayName : 'Usuario';
    @endphp

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
                <input class="input" type="text"
                    placeholder="Buscar recursos o perfiles..."
                    data-live-search
                    data-search-url="{{ route('feed.search') }}">
                <button class="reset" type="reset">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </form>
        </div>

        <div class="k-header-actions">
            <!--Notificaciones-->
            <a href="{{ route('notifications.index') }}" class="notifications-button">
                <svg viewBox="0 0 448 512" class="bell">
                    <path d="M224 0c-17.7 0-32 14.3-32 32V49.9C119.5 61.4 64 124.2 64 200v33.4c0 45.4-15.5 89.5-43.8 124.9L5.3 377c-5.8 7.2-6.9 17.1-2.9 25.4S14.8 416 24 416H424c9.2 0 17.6-5.3 21.6-13.6s2.9-18.2-2.9-25.4l-14.9-18.6C399.5 322.9 384 278.8 384 233.4V200c0-75.8-55.5-138.6-128-150.1V32c0-17.7-14.3-32-32-32zm0 96h8c57.4 0 104 46.6 104 104v33.4c0 47.9 13.9 94.6 39.7 134.6H72.3C98.1 328 112 281.3 112 233.4V200c0-57.4 46.6-104 104-104h8zm64 352H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7s18.7-28.3 18.7-45.3z"></path>
                </svg>
                @if ($hasUnreadNotifications ?? false)
                <span class="notification-red-dot"></span>
                @endif
            </a>
            <!--Perfil-->
            @auth
            <div class="k-profile" tabindex="0">
                <button class="k-profile-btn" type="button" aria-haspopup="true" aria-expanded="false">
                    <x-user-avatar :user="$profileUser" alt="Avatar de {{ $displayName }}" />
                    <span class="k-profile-name">{{ $profileUser?->name ?? 'Usuario' }}</span>
                    <span class="k-profile-chev">▾</span>
                </button>

                <div class="k-profile-menu" role="menu" aria-label="Perfil">
                    <a class="k-profile-item" href="{{ route('profile.me') }}" role="menuitem">Perfil</a>
                    @if (strtoupper((string) $profileUser->role) === 'ADMIN')
                    <a class="k-profile-item" href="{{ route('admin.teacher-requests.index') }}" role="menuitem">Solicitudes</a>
                    @endif
                    @if (strtoupper((string) $profileUser->role) === 'ADMIN')
                    <a href="{{ route('admin.reports.index') }}" class="k-profile-item">
                        Denuncias
                    </a>
                    @endif
                    @if (strtoupper((string) $profileUser->role) === 'ADMIN')
                    <a href="{{ route('admin.incidents.index') }}" class="k-profile-item">
                        Incidencias
                    </a>
                    @endif
                    @if (strtoupper((string) $profileUser->role) === 'ADMIN')
                    <a href="{{ route('admin.users.index') }}" class="k-profile-item">
                        Usuarios
                    </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="k-profile-item" type="submit" role="menuitem">Cerrar sesión</button>
                    </form>
                </div>
            </div>
            @else
            <a class="k-profile-btn" href="{{ route('login') }}">Iniciar sesión</a>
            @endauth
        </div>


    </div>
</header>