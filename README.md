# Klassify

Klassify és una plataforma educativa per compartir recursos, descobrir materials útils i interactuar dins d’una comunitat escolar.

## Què fa

- Compartir apunts, documents, imatges, àudios, vídeos i enllaços.
- Seguir altres usuaris i veure el seu contingut.
- Guardar favorits, fer likes i comentar recursos.
- Filtrar contingut per curs, assignatura i tipus de fitxer.
- Gestionar perfils, notificacions, denúncies i incidències.

## Tecnologia

- **Laravel 12** per a l’aplicació web.
- **PHP 8.2** per a la lògica del servidor.
- **MySQL** per a la base de dades.
- **Blade** per a les vistes.
- **JavaScript modular** per a les parts dinàmiques del feed.
- **Vite** per compilar els recursos estàtics.
- **CSS personalitzat** per al disseny.
- **AWS S3** per guardar fitxers i fotos de perfil.
- **pdfjs-dist** per a la visualització de PDF.

## Base de dades

He fet servir **MySQL** perquè em permet treballar amb una estructura relacional clara i amb dades ben vinculades entre usuaris, recursos, comentaris, favorits, likes, seguiments i notificacions.

La base de dades s’ha construït amb **migracions** per tenir l’esquema controlat i poder aplicar canvis de manera ordenada i precisa. 

He intentat que les relacions entre models estiguin ben definides des del backend, perquè això facilita consultar la informació, filtrar continguts i mantenir el projecte més net a nivell de codi.

## Decisions de projecte

### Laravel i Blade

He triat Laravel perquè em dona una estructura clara amb MVC, control de rutes, middleware, validació i Eloquent. Blade m’ha anat bé per reutilitzar plantilles, components i parcials, i així evitar repetir codi en el capçalera, el peu de pàgina, els avatars i els errors personalitzats.

### Feed modular

El feed està separat en mòduls JavaScript perquè té moltes funcions diferents: favorits, menús, previsualització, àudio, filtres i càrrega dinàmica. D’aquesta manera el codi és més fàcil de mantenir i d’entendre.

### Emmagatzematge a S3

Els fitxers s’han guardat a AWS S3 per no dependre del disc local. Això fa que l’aplicació sigui més propera a un entorn real i simplifica la gestió de recursos pesats com documents o imatges.

### SEO i errors personalitzats

També he cuidat la part de SEO amb `meta description`, `canonical`, `Open Graph`, `Twitter Cards`, `sitemap` i `robots.txt`. A més, he creat pàgines personalitzades per als errors 403 i 404 perquè l’experiència visual sigui coherent.

### Rutes web

El fitxer `routes/web.php` està organitzat per blocs segons el tipus d’usuari i la funció de cada part de l’aplicació. Hi ha una ruta d’inici per a convidats, pàgines públiques com `sobre-klassify`, `normes-comunidad` i `privacitat`, rutes d’autenticació, el feed principal per a usuaris registrats, el perfil, els recursos, les notificacions i les zones d’administració.

També he separat algunes rutes per middleware perquè només hi puguin entrar els usuaris que toca. Per exemple, les accions d’edició, creació i administració estan protegides, i això evita accessos no autoritzats.

## Estructura general

- `app/`: controladors, models, serveis, middleware i notificacions.
- `resources/views/`: plantilles Blade i components.
- `resources/js/`: comportament dinàmic de la part visual.
- `public/`: fitxers públics, imatges i recursos compilats.
- `routes/`: rutes web.
- `database/`: migracions, seeders i factories.

## Instal·lació

### 1. Dependències de PHP

```bash
composer install
```

### 2. Dependències de la part visual

```bash
npm install
```

### 3. Configuració

Copia el fitxer `.env.example` a `.env` si cal i configura com a mínim:

- `APP_URL`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`
- variables d’AWS si vols fer servir S3

### 4. Clau de Laravel

```bash
php artisan key:generate
```

### 5. Migracions i dades

```bash
php artisan migrate --seed
```

### 6. Compilar recursos

```bash
npm run build
```

### 7. Arrencar el projecte

```bash
php artisan serve
```

## Comandes útils

```bash
php artisan view:cache
php artisan route:cache
php artisan config:cache
php artisan optimize
php artisan optimize:clear
php artisan test
```

## Resum

Klassify està pensat per organitzar recursos educatius d’una manera clara, visual i funcional, amb una base tècnica ordenada i preparada per créixer.
