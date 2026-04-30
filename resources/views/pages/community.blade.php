@extends('layouts.app')

@section('title', 'Normas de la comunidad')

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