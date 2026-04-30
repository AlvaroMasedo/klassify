@extends('layouts.app')

@section('title', 'Privacidad')

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/static-pages.css') }}">
@endsection

@section('content')
<main class="static-page">
    <section class="static-card">
        <span class="static-kicker">Privacidad</span>

        <h1>Privacidad en Klassify</h1>

        <p>
            En Klassify tratamos la información de los usuarios con responsabilidad. Esta página resume cómo se utiliza
            la información dentro de la plataforma.
        </p>

        <div class="static-list">
            <article>
                <h2>Datos de cuenta</h2>
                <p>
                    Guardamos los datos necesarios para identificar tu cuenta, como nombre, usuario, correo, rol,
                    centro educativo y otra información asociada a tu perfil.
                </p>
            </article>

            <article>
                <h2>Contenido que publicas</h2>
                <p>
                    Los recursos, comentarios, likes, favoritos y seguimientos forman parte de tu actividad dentro de Klassify.
                    Esta información se utiliza para mostrar contenido, ordenar el feed y mejorar la experiencia.
                </p>
            </article>

            <article>
                <h2>Denuncias e incidencias</h2>
                <p>
                    Cuando envías una denuncia o una incidencia, guardamos la información necesaria para que los
                    administradores puedan revisarla y resolverla.
                </p>
            </article>

            <article>
                <h2>Perfiles privados</h2>
                <p>
                    Si tu perfil está marcado como privado, se limitará la visibilidad de tu información y de tus recursos
                    dentro de la plataforma.
                </p>
            </article>

            <article>
                <h2>Seguridad</h2>
                <p>
                    No compartas tu contraseña con nadie. Si detectas un problema de seguridad, utiliza la página de Ayuda
                    para enviar una incidencia.
                </p>
            </article>
        </div>

        <div class="static-actions">
            <a href="{{ auth()->check() ? route('feed') : route('home') }}" class="static-primary-btn">
                Volver
            </a>
        </div>
    </section>
</main>
@endsection