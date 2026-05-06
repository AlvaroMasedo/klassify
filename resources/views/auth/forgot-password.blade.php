@extends('layouts.auth')

@section('title', 'Recuperar contraseña')

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/forgot-password.css') }}">
@endsection

@section('content')
<main>
    <div class="k-content k-forgot-content">
        <div class="k-content-title">
            <img class="k-logo" src="/assets/img/k-logo.png" alt="Logo de klassify">
            <span class="k-name">KLASSIFY</span>
        </div>

        <div class="k-content-body">
            <section class="k-forgot-hero" aria-label="Información de recuperación">
                <h1>Recupera el acceso a tu cuenta</h1>
                <p>Introduce tu correo y te enviaremos un enlace para restablecer la contraseña.</p>

                <div class="k-forgot-illustration" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="137.6761 128.2611 93.7796 63.6695" preserveAspectRatio="xMidYMid meet">
                        <defs>
                            <linearGradient id="envBody" x1="35" y1="55" x2="125" y2="115" gradientUnits="userSpaceOnUse">
                                <stop offset="0" stop-color="#C9A8F0" />
                                <stop offset="1" stop-color="#B88AE8" />
                            </linearGradient>
                            <linearGradient id="envFlap" x1="35" y1="55" x2="80" y2="92" gradientUnits="userSpaceOnUse" gradientTransform="matrix(1, 0, 0, 1, -0.216748, -0.650245)">
                                <stop offset="0" stop-color="#E9DAF8" />
                                <stop offset="1" stop-color="#D7BDF4" />
                            </linearGradient>
                            <linearGradient gradientUnits="userSpaceOnUse" x1="92.28" y1="87.911" x2="92.28" y2="96.207" id="gradient-1" gradientTransform="matrix(1.137498, 0, 0, 1.313523, -36.314011, -27.562092)">
                                <stop offset="0" stop-color="#EFE4FA" />
                                <stop offset="1" stop-color="#D4BAF2" />
                            </linearGradient>
                            <linearGradient id="lockBody" x1="98" y1="70" x2="132" y2="104" gradientUnits="userSpaceOnUse" gradientTransform="matrix(0.473611, 0, 0, 0.586823, 11.023441, 52.168785)">
                                <stop offset="0" stop-color="#B98AE9" />
                                <stop offset="1" stop-color="#9F5BDF" />
                            </linearGradient>
                        </defs>
                        <g transform="matrix(1, 0, 0, 1, 102.89311981201172, 73.91114044189453)">
                            <rect x="35" y="55" width="90" height="60" rx="8" fill="url(#envBody)" />
                            <path d="M 36.083 63 L 79.891 92 L 123.7 63" stroke="#EADCF8" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" opacity="0.95" style="" />
                            <path d="M 34.783 60.35 C 34.783 57.05 37.483 54.35 40.783 54.35 L 118.783 54.35 C 122.083 54.35 124.783 57.05 124.783 60.35 L 124.783 62.35 L 79.783 91.35 L 34.783 62.35 L 34.783 60.35 Z" fill="url(#envFlap)" />
                            <path d="M 121.316 111.966 L 91.867 87.217" stroke="#D9C0F2" stroke-width="2.5" stroke-linecap="round" opacity="0.55" style="" />
                            <path d="M 67.437 86.957 L 37.93 111.706" stroke="#D9C0F2" stroke-width="2.5" stroke-linecap="round" opacity="0.55" style="stroke-width: 2.5; transform-box: fill-box; transform-origin: 50% 50%;" transform="matrix(-1, 0, 0, -1, 0.00001, 0.000005)" />
                        </g>
                        <g transform="matrix(1, 0, 0, 1, 153.1787567138672, 75.21163177490234)">
                            <path d="M 61.483 98.808 L 61.483 95.538 C 61.483 91.179 64.558 87.911 68.656 87.911 C 72.754 87.911 75.828 91.179 75.828 95.538 L 75.828 98.808" stroke-linecap="round" style="fill: rgba(0, 0, 0, 0); stroke: url(&quot;#gradient-1&quot;); stroke-width: 3px;" />
                            <rect x="58.385" y="97.941" width="19.892" height="18.778" fill="url(#lockBody)" style="" rx="2.532" ry="2.532" />
                            <path d="M 64.981 106.45 C 64.981 104.783 66.466 103.473 68.356 103.473 C 70.245 103.473 71.73 104.783 71.73 106.45 C 71.73 107.641 70.92 108.653 69.705 109.129 L 69.705 112.404 L 67.006 112.404 L 67.006 109.129 C 65.791 108.653 64.981 107.641 64.981 106.45 Z" fill="#F3EBFC" opacity="0.95" style="" />
                        </g>
                    </svg>
                </div>
            </section>

            <section class="k-forgot-card" aria-label="Formulario de recuperación">
                <h2>Restablecer contraseña</h2>
                <p>Te enviaremos un correo con los pasos para crear una nueva contraseña.</p>

                @if (session('status'))
                <div class="k-status-message" role="status">
                    {{ session('status') }}
                </div>
                @endif

                <form method="POST" action="{{ route('forgot.password.email') }}">
                    @csrf

                    <label for="email">Email
                        <input type="email" id="email" name="email" placeholder="Escribe tu email" autocomplete="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                        <p class="p-error">{{ $message }}</p>
                        @enderror
                    </label>

                    <div class="k-form-actions">
                        <button type="submit" class="k-submit-btn">Enviar enlace de recuperación</button>
                        <p class="k-auth-switch">
                            <a class="k-auth-switch-link" href="{{ route('login') }}">Volver al inicio de sesión</a>
                        </p>
                    </div>
                </form>
            </section>
        </div>
    </div>
</main>
@endsection