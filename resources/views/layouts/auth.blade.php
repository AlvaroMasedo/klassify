<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Klassify')</title>

    <link rel="icon" href="{{ asset('Favicon.ico') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/partials/auth-header.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/partials/auth-footer.css') }}">
    @yield('page-css')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
</head>

<body class="k-body" data-page="@yield('page')">

    <header class="k-auth-header">
        <div class="k-auth-header-inner">
            <div class="k-auth-header-actions">
                @if (request()->routeIs('register'))
                <a href="{{ route('login') }}" class="k-btn k-btn--ghost">Iniciar sesión</a>
                @elseif (request()->routeIs('login'))
                <a href="{{ route('register') }}" class="k-btn k-btn--ghost">Registrarse</a>
                @endif
            </div>
        </div>
    </header>

    <main class="k-auth-main">
        @yield('content')
    </main>

    <footer class="k-auth-footer">
        <div class="k-auth-footer-links">
            <a href="#">Sobre Klassify · </a>
            <a href="#">Normas de la comunidad · </a>
            <a href="#">Privacidad · </a>
            @auth
            <a href="{{ route('incidents.create') }}">Ayuda · </a>
            @else
            <a href="{{ route('login') }}">Ayuda · </a>
            @endauth
            <a href="#">Contacto</a>
        </div>
        <span>© {{ date('Y') }} Klassify</span>
    </footer>

</body>

</html>