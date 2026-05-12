# LibrePress Laravel CMS

LibrePress est une proposition d'alternative moderne a WordPress, basee sur Laravel, Filament, TailwindCSS et une architecture modulaire stricte.

Objectifs:

- libre, auto-hebergeable et compatible petits VPS/Raspberry Pi;
- coeur minimal, extensions isolees, themes dynamiques;
- API REST native, RSS/Atom, PWA et support ActivityPub;
- administration moderne avec Filament;
- confidentialite par defaut, telemetrie absente, federation optionnelle;
- compatible Docker Compose et empaquetable YunoHost.

Ce depot pose une fondation d'architecture et des contrats de code. Il n'est pas encore un projet Laravel installe par Composer; il sert de socle pour generer l'application.

Etat actuel: MVP Laravel fonctionnel avec front public, admin, contenus, medias, commentaires, recherche, RSS, PWA, API REST, modules/themes locaux, export, backup et import WordPress WXR minimal.

## Stack cible

- PHP 8.3+ avec typage strict;
- Laravel 11/12 selon la version LTS disponible au moment de l'initialisation;
- Filament Admin 3/4 selon compatibilite Laravel;
- MariaDB 10.11+ ou PostgreSQL 15+;
- Redis pour cache, queues et rate limiting;
- TailwindCSS, Livewire pour l'administration, Vue possible pour l'editeur bloc;
- Docker Compose pour l'auto-hebergement;
- Nginx + PHP-FPM en production.

## Principes d'architecture

Le coeur ne connait pas les modules metier. Les modules parlent au coeur via des contrats:

- `ModuleInterface` pour le cycle de vie;
- `HookRegistryInterface` pour les points d'extension;
- `ThemeInterface` pour les themes;
- `PermissionRegistrarInterface` pour les permissions;
- events Laravel pour les integrations asynchrones.

Les themes ne contiennent pas de logique applicative critique. Les plugins/modules ne modifient jamais les tables du coeur directement hors migrations declarees.

## Structure

```text
app/
  Core/
    Contracts/       Contrats stables exposes aux modules et themes
    Hooks/           Registre moderne de hooks filtres/actions
    Modules/         Decouverte, activation et cycle de vie modules
    Permissions/     Permissions, roles, policies
    Themes/          Resolution theme, regions, assets
  Models/            Modeles coeur
modules/
  Blog/              Exemple de module installable
themes/
  Nova/              Exemple de theme dynamique
docs/
  ARCHITECTURE.md
  DATA_MODEL.md
  SECURITY_PERFORMANCE.md
docker/
nginx/
yunohost/
```

## Demarrage cible

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

## Demarrage local SQLite

```bash
composer install
php artisan migrate:fresh --seed
php artisan serve --host=127.0.0.1 --port=8001
```

Identifiants initiaux:

- email: `admin@example.test`
- mot de passe: `password`

## Fonctionnalites disponibles

- pages et articles;
- editeur Markdown stocke comme bloc JSON;
- SEO par contenu;
- commentaires et moderation;
- medias;
- recherche interne;
- RSS;
- API REST lecture;
- endpoint ActivityPub minimal;
- PWA basique;
- modules locaux activables;
- themes locaux activables;
- reglages site;
- export JSON;
- backup SQLite local;
- import WordPress WXR minimal.

Commandes utiles:

```bash
php artisan librepress:export
php artisan librepress:backup
php artisan librepress:import-wordpress /chemin/export.wordpress.xml
```

## Modules

Un module est un package local versionne:

```text
modules/Blog/
  module.json
  src/BlogModule.php
  database/migrations/
  resources/views/
  routes/api.php
  routes/web.php
```

Le manifeste declare le fournisseur, les permissions, les migrations, les hooks et les dependances.

## Themes

Un theme est un package de presentation:

```text
themes/Nova/
  theme.json
  resources/views/
  assets/
```

Les themes consomment des regions (`header`, `content`, `sidebar`, `footer`) et des composants bloc. Ils ne doivent pas appeler directement les modeles metier complexes.

## Documentation

- [Architecture](docs/ARCHITECTURE.md)
- [Modele de donnees](docs/DATA_MODEL.md)
- [Securite et performance](docs/SECURITY_PERFORMANCE.md)
# -LibrePress-
