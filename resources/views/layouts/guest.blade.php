<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta-description', 'Klassify es una plataforma educativa para compartir recursos, apuntes, exámenes y materiales entre profesores y alumnos.')">
    <meta name="keywords" content="@yield('meta-keywords', 'Klassify, recursos educativos, apuntes, exámenes, profesores, alumnos, comunidad educativa, material escolar, aprendizaje online')">
    <meta name="robots" content="@yield('meta-robots', 'index, follow')">
    <link rel="canonical" href="@yield('canonical-url', url()->current())">

    <meta property="og:locale" content="es_ES">
    <meta property="og:type" content="@yield('og-type', 'website')">
    <meta property="og:site_name" content="Klassify">
    <meta property="og:title" content="@yield('og-title', trim($__env->yieldContent('title', 'Klassify')))">
    <meta property="og:description" content="@yield('og-description', trim($__env->yieldContent('meta-description', 'Klassify es una plataforma educativa para compartir recursos, apuntes, exámenes y materiales entre profesores y alumnos.')))">
    <meta property="og:url" content="@yield('canonical-url', url()->current())">
    <meta property="og:image" content="@yield('og-image', asset('assets/img/k-logo.png'))">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter-title', trim($__env->yieldContent('title', 'Klassify')))">
    <meta name="twitter:description" content="@yield('twitter-description', trim($__env->yieldContent('meta-description', 'Klassify es una plataforma educativa para compartir recursos, apuntes, exámenes y materiales entre profesores y alumnos.')))">
    <meta name="twitter:image" content="@yield('twitter-image', asset('assets/img/k-logo.png'))">
    @yield('structured-data')
    <title>@yield('title', 'Klassify')</title>
    <link rel="icon" href="{{ asset('Favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('page-css')

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
</head>

<body class="k-body">
    <main>
        @yield('content')
    </main>
</body>

</html>