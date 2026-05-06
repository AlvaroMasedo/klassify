@extends('layouts.app')

@section('title', 'Normas de la comunidad')
@section('meta-description', 'Consulta las normas de la comunidad de Klassify para mantener un entorno educativo útil y seguro.')
@section('meta-keywords', 'normas de la comunidad, Klassify, recursos educativos, convivencia digital, profesores, alumnos, comunidad escolar')
@section('meta-robots', 'index, follow')
@section('canonical-url', route('pages.community'))
@section('og-title', 'Normas de la comunidad | Klassify')
@section('og-description', 'Normas básicas para mantener Klassify como una comunidad educativa útil y segura.')
@section('og-image', asset('assets/img/k-logo.png'))
@section('twitter-title', 'Normas de la comunidad | Klassify')
@section('twitter-description', 'Normas básicas para mantener Klassify como una comunidad educativa útil y segura.')
@section('structured-data')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebPage',
    'name' => 'Normas de la comunidad',
    'url' => route('pages.community'),
    'description' => 'Normas básicas para mantener Klassify como una comunidad educativa útil y segura.',
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    '@id' => route('pages.community') . '#breadcrumb',
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
            'name' => 'Normas de la comunidad',
            'item' => route('pages.community'),
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
        <span class="static-kicker">Comunidad</span>

        <h1>Normas de la comunidad</h1>

        <p>
            Para que Klassify sea un espacio útil y seguro, todos los usuarios deben respetar unas normas básicas de convivencia.
        </p>

        <div class="static-list">
            <article>
                <h2>1. Respeta a los demás usuarios</h2>
                <p>
                    No se permite insultar, acosar, amenazar o molestar a otros usuarios mediante comentarios,
                    recursos o mensajes.
                </p>
            </article>

            <article>
                <h2>2. Comparte contenido adecuado</h2>
                <p>
                    Los recursos deben estar relacionados con el aprendizaje. No subas contenido ofensivo, ilegal,
                    engañoso o que no tenga relación con la finalidad educativa de la plataforma.
                </p>
            </article>

            <article>
                <h2>3. No publiques datos privados</h2>
                <p>
                    No compartas información personal propia o de terceros, como direcciones, teléfonos, contraseñas
                    o datos sensibles.
                </p>
            </article>

            <article>
                <h2>4. Usa correctamente las denuncias</h2>
                <p>
                    Puedes denunciar recursos o comentarios que incumplan las normas. Las denuncias falsas o abusivas
                    también pueden ser revisadas por los administradores.
                </p>
            </article>

            <article>
                <h2>5. Respeta la autoría</h2>
                <p>
                    No publiques contenido de otras personas como si fuera tuyo. Si compartes material externo,
                    asegúrate de tener permiso o de citar su origen cuando sea necesario.
                </p>
            </article>
        </div>

        <div class="static-actions">
            <a href="{{ auth()->check() ? route('feed') : route('home') }}" class="static-primary-btn">
                Entendido
            </a>
        </div>
    </section>
</main>
@endsection