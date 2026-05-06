<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Acceso denegado | Klassify</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="{{ asset('Favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: light;
            --bg: #f6f2ed;
            --surface: #ffffff;
            --surface-2: #f1e8df;
            --text: #2d1b3d;
            --muted: #6f5f72;
            --accent: #2d1b3d;
            --accent-2: #6d4a7d;
            --shadow: 0 24px 60px rgba(45, 27, 61, 0.14);
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            min-height: 100%;
            font-family: 'Open Sans', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(109, 74, 125, 0.12), transparent 35%),
                radial-gradient(circle at bottom right, rgba(45, 27, 61, 0.08), transparent 30%),
                var(--bg);
            color: var(--text);
        }

        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .error-card {
            width: min(100%, 760px);
            background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(255,255,255,0.92));
            border: 1px solid rgba(45, 27, 61, 0.08);
            border-radius: 28px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .error-hero {
            padding: 42px 36px 20px;
            background: linear-gradient(135deg, rgba(45, 27, 61, 0.06), rgba(109, 74, 125, 0.12));
            text-align: center;
        }

        .error-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(45, 27, 61, 0.08);
            color: var(--accent);
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            font-size: 0.82rem;
        }

        .error-code {
            margin: 18px 0 10px;
            font-size: clamp(4rem, 10vw, 7rem);
            line-height: 0.95;
            font-weight: 800;
            color: var(--accent);
        }

        .error-title {
            margin: 0;
            font-size: clamp(1.5rem, 3vw, 2.2rem);
            font-weight: 800;
        }

        .error-body {
            padding: 28px 36px 38px;
        }

        .error-body p {
            margin: 0;
            color: var(--muted);
            font-size: 1.03rem;
            line-height: 1.7;
        }

        .error-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 28px;
        }

        .error-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            padding: 0 18px;
            border-radius: 14px;
            text-decoration: none;
            font-weight: 700;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .error-button:hover {
            transform: translateY(-1px);
        }

        .error-button--primary {
            background: var(--accent);
            color: #fff;
            box-shadow: 0 16px 28px rgba(45, 27, 61, 0.18);
        }

        .error-button--secondary {
            background: var(--surface-2);
            color: var(--accent);
        }

        .error-meta {
            margin-top: 18px;
            font-size: 0.92rem;
            color: var(--muted);
        }

        @media (max-width: 640px) {
            .error-hero,
            .error-body {
                padding-left: 22px;
                padding-right: 22px;
            }

            .error-actions {
                flex-direction: column;
            }

            .error-button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <main class="error-card" role="main" aria-labelledby="error-title">
        <section class="error-hero">
            <span class="error-badge">Klassify</span>
            <div class="error-code">403</div>
            <h1 id="error-title" class="error-title">Acceso denegado</h1>
        </section>

        <section class="error-body">
            <p>
                No tienes permisos para ver esta página o realizar esta acción. Si crees que es un error,
                vuelve al inicio o inicia sesión con una cuenta autorizada.
            </p>

            <div class="error-actions">
                <a class="error-button error-button--primary" href="{{ route('home') }}">Ir al inicio</a>
                <a class="error-button error-button--secondary" href="{{ auth()->check() ? route('feed') : route('login') }}">
                    {{ auth()->check() ? 'Volver al feed' : 'Iniciar sesión' }}
                </a>
            </div>

            <div class="error-meta">
                Si necesitas acceso a esta sección, contacta con un administrador.
            </div>
        </section>
    </main>
</body>
</html>