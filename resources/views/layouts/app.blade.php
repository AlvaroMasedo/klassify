<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Klassify')</title>
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

<body class="k-body" data-page="@yield('page')">
    @include('layouts.partials.header')

    @if(session('error') || session('success') || session('status'))
    <div class="k-alert-stack" aria-live="polite" aria-atomic="true">
        @if(session('error'))
        <div class="k-alert k-alert--error js-k-alert" role="alert" data-autohide="5000">
            {{ session('error') }}
        </div>
        @endif

        @if(session('success'))
        <div class="k-alert k-alert--success js-k-alert" role="status" data-autohide="5000">
            {{ session('success') }}
        </div>
        @endif

        @if(session('status'))
        <div class="k-alert k-alert--success js-k-alert" role="status" data-autohide="5000">
            {{ session('status') }}
        </div>
        @endif
    </div>
    @endif

    <main class="k-main">
        @yield('content')
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.js-k-alert').forEach(function(alert) {
                var timeoutMs = Number(alert.dataset.autohide || 5000);

                window.setTimeout(function() {
                    alert.classList.add('k-alert--hide');

                    window.setTimeout(function() {
                        alert.remove();
                    }, 320);
                }, timeoutMs);
            });
        });
    </script>
</body>

</html>