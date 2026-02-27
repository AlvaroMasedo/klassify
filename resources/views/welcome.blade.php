@extends('layouts.guest')

@section('title', 'Bienvenido a Klassify')

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/welcome.css') }}">
@endsection

@section('content')
<main class="k-welcome-background">

    <header class="k-title">
        <img src="{{ asset('assets/img/k-logo.png') }}" alt="Klassify Logo" class="k-logo">
        <h1>KLASSIFY</h1>
    </header>

    <section class="k-content">
        <h2>Comparte y descubre recursos educativos</h2>

        <div class="k-features">
            <div class="k-feature">
                <div class="k-feature__icon">
                    <svg viewBox="0 0 24 24" width="36" height="36" stroke="#2d1b3d" stroke-width="2" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <div class="k-feature__text">
                    <p class="p-stroke">Recursos por curso y asignatura</p>
                </div>
            </div>

            <div class="k-feature">
                <div class="k-feature__icon">
                    <svg fill="none" height="55" viewBox="0 0 120 120" width="55" xmlns="http://www.w3.org/2000/svg">
                        <path d="m25 49c0-7.732 6.268-14 14-14h42c7.732 0 14 6.268 14 14v22c0 7.732-6.268 14-14 14h-42c-7.732 0-14-6.268-14-14z"
                            stroke="#2d1b3d" stroke-width="5"></path>
                        <path d="m74 59.5-21 10.8253v-21.6506z" fill="#2d1b3d"></path>
                    </svg>
                </div>
                <div class="k-feature__text">
                    <p>Videos, documentos, enlaces, imágenes y audios</p>
                    <p class="p-pequeño">(examenes solo para profesores)</p>
                </div>
            </div>

            <div class="k-feature">
                <div class="k-feature__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="37" height="37.5" viewBox="0 0 17.503 15.625">
                        <path d="M8.752,15.625h0L1.383,8.162a4.824,4.824,0,0,1,0-6.762,4.679,4.679,0,0,1,6.674,0l.694.7.694-.7a4.678,4.678,0,0,1,6.675,0,4.825,4.825,0,0,1,0,6.762L8.752,15.624ZM4.72,1.25A3.442,3.442,0,0,0,2.277,2.275a3.562,3.562,0,0,0,0,5l6.475,6.556,6.475-6.556a3.563,3.563,0,0,0,0-5A3.443,3.443,0,0,0,12.786,1.25h-.01a3.415,3.415,0,0,0-2.443,1.038L8.752,3.9,7.164,2.275A3.442,3.442,0,0,0,4.72,1.25Z"
                            fill="#2d1b3d" transform="translate(0 0)"></path>
                    </svg>
                </div>
                <div class="k-feature__text">
                    <p>
                        <span class="p-stroke">Favoritos</span>,
                        comentarios, seguir y feed "Para ti"
                    </p>
                </div>
            </div>

            <div class="k-feature k-feature--note">
                <div class="k-feature__icon"></div>
                <div class="k-feature__text">
                    <p class="p-pequeño">
                        <span class="p-new">Nuevo</span>
                        Profes verificados antes de publicar
                    </p>
                </div>
            </div>

        </div>
    </section>

    <div class="k-card">
        <h3>Entra a Klassify</h3>
        <p>Empieza a descubrir recursos como profesor o alumno</p>

        <a href="#" class="k-button">Crear Cuenta</a>
        <a href="#" class="k-button-second">Iniciar Sesión</a>

        <p class="p-pequeño">Al registrarte debes aceptar los términos y normas de la plataforma</p>
    </div>

</main>
@endsection