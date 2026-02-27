<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Klassify')</title>
    <link rel="icon" href="{{ asset('Favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/partials/header.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/partials/footer.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('page-css')
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
</head>

<body class="k-body">
    @include('layouts.partials.header')

    <main class="k-main">
        @yield('content')
    </main>
</body>

</html>