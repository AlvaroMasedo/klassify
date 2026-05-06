@extends('layouts.app')

@section('title', 'Sobre Klassify')
@section('meta-description', 'Conoce Klassify, una plataforma para compartir y descubrir recursos educativos de forma sencilla.')
@section('meta-keywords', 'sobre Klassify, plataforma educativa, recursos educativos, apuntes, exámenes, profesores, alumnos, comunidad escolar')
@section('meta-robots', 'index, follow')
@section('canonical-url', route('pages.about'))
@section('og-title', 'Sobre Klassify')
@section('og-description', 'Conoce Klassify y descubre cómo ayuda a profesores y alumnos a compartir recursos educativos.')
@section('og-image', asset('assets/img/k-logo.png'))
@section('twitter-title', 'Sobre Klassify')
@section('twitter-description', 'Plataforma para compartir y descubrir recursos educativos de forma sencilla.')
@section('structured-data')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'AboutPage',
    'name' => 'Sobre Klassify',
    'url' => route('pages.about'),
    'description' => 'Klassify es una plataforma para compartir y descubrir recursos educativos de forma sencilla.',
    'breadcrumb' => [
        '@id' => route('pages.about') . '#breadcrumb',
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    '@id' => route('pages.about') . '#breadcrumb',
    'itemListElement' => [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Inicio',
            'item' => route('home'),
        ],
        [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => 'Sobre Klassify',
            'item' => route('pages.about'),
        ],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endsection

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/static-pages.css') }}">
@endsection

@section('content')
<main class="static-page">
    <section class="static-card">
        <span class="static-kicker">Sobre Klassify</span>

        <h1>Una plataforma para compartir recursos educativos</h1>

        <p>
            Klassify es una plataforma creada para que estudiantes, profesores y centros educativos puedan compartir,
            descubrir y organizar recursos de aprendizaje de una forma sencilla.
        </p>

        <div class="static-grid">
            <article>
                <h2>Qué puedes hacer</h2>
                <p>
                    Puedes subir apuntes, documentos, imágenes, audios, vídeos y materiales relacionados con tus cursos.
                    También puedes guardar favoritos, dar like, comentar y seguir a otros usuarios.
                </p>
            </article>

            <article>
                <h2>Para estudiantes</h2>
                <p>
                    Klassify ayuda a encontrar recursos útiles según tu curso, tus intereses y los perfiles que sigues.
                </p>
            </article>

            <article>
                <h2>Para profesores</h2>
                <p>
                    Los profesores pueden compartir materiales con la comunidad y facilitar el acceso a contenido de calidad.
                </p>
            </article>

            <article>
                <h2>Comunidad</h2>
                <p>
                    La plataforma se basa en el respeto, la colaboración y el uso responsable de los recursos compartidos.
                </p>
            </article>
        </div>

        <div class="static-actions">
            <a href="{{ auth()->check() ? route('feed') : route('home') }}" class="static-primary-btn">
                Volver a Klassify
            </a>
        </div>
    </section>
</main>
@endsection