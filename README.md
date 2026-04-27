# Klassify

Projecte Final 2n DAW (2025/2026)

## Stack
- Laravel (PHP)
- MySQL
- AWS S3 (fitxers)
- Frontend: HTML/CSS/JS + React per UI dinàmica

## Branques
- main: estable (releases)
- develop: integració del desenvolupament
- feature/*: funcionalitats per tasca
- release/*: tancament de versió

## Desplegament / Producció

Abans de publicar una versió en producció, executa aquests comandos per optimitzar dependències, generar els assets de Vite i preparar les cachés de Laravel:

```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

Si cal netejar les cachés per resoldre incidències de desplegament o configuració, utilitza:

```bash
php artisan optimize:clear
```