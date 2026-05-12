# Architecture technique

## Vision

LibrePress doit rester plus proche d'un noyau applicatif Laravel propre que d'un CMS monolithique historique. Le coeur fournit les capacites transverses: contenu, identite, permissions, medias, themes, modules, API, cache, jobs, audit et configuration. Les fonctionnalites avancees vivent dans des modules activables.

## Couches

```text
HTTP/API
  Controllers REST, resources Filament, middleware securite
Application
  Actions, DTO, validation, policies, events
Domain
  Modeles coeur, value objects, contrats publics
Infrastructure
  Eloquent, filesystem, queue, cache, search, federation
Extensions
  Modules, themes, hooks, blocks
```

Regle importante: un module peut dependre du coeur, mais le coeur ne depend jamais d'un module.

## Coeur minimal

Le coeur doit embarquer seulement:

- pages;
- medias;
- utilisateurs, roles, permissions;
- themes;
- modules;
- configuration;
- hooks/events;
- API REST;
- cache;
- audit securite;
- import/export.

Blog, commentaires, SEO avance, ActivityPub, recherche avancee et PWA peuvent etre des modules officiels installes par defaut.

## Modules

Cycle de vie d'un module:

1. decouverte du manifeste `module.json`;
2. verification signature/checksum si installation distante;
3. validation des contraintes de version;
4. enregistrement des services dans un container scope;
5. execution des migrations du module;
6. enregistrement routes, permissions, blocs, hooks et widgets Filament;
7. activation atomique en base.

Les modules sont isoles par convention:

- namespace dedie;
- migrations prefixees;
- permissions prefixees (`blog.posts.create`);
- tables prefixees lorsque le domaine n'est pas coeur;
- assets compiles dans un dossier public module;
- pas d'ecriture directe dans les fichiers coeur.

## Hooks modernes

Deux mecanismes coexistent:

- Events Laravel pour les integrations fortement typees et asynchrones;
- Hook registry pour actions/filtres synchrones inspires WordPress, mais types.

Exemples:

```php
$hooks->action('content.published', fn (ContentPublished $event) => ...);
$html = $hooks->filter('render.block.html', $html, $blockContext);
```

Chaque hook declare:

- nom stable;
- payload type;
- type de retour pour les filtres;
- priorite;
- module proprietaire.

## Themes

Un theme declare:

- layouts disponibles;
- regions;
- composants de blocs supportes;
- variables design;
- assets;
- compatibilite avec les versions du coeur.

Le rendu suit l'ordre:

1. contenu charge via repository;
2. blocs normalises;
3. resolution layout/theme;
4. application des filtres de rendu;
5. cache page/fragment;
6. reponse HTML, JSON, RSS ou ActivityPub selon negotiation.

## Editeur bloc

Le contenu est stocke sous forme de document JSON versionne:

```json
{
  "version": 1,
  "blocks": [
    {
      "id": "hero-1",
      "type": "core/heading",
      "props": {"level": 1, "text": "Bonjour"}
    }
  ]
}
```

Chaque bloc a:

- un schema JSON;
- une vue publique;
- un composant d'edition;
- une policy facultative;
- une strategie de sanitization.

## API REST

Routes cibles:

```text
GET    /api/v1/pages
POST   /api/v1/pages
GET    /api/v1/posts
GET    /api/v1/media
POST   /api/v1/media
GET    /api/v1/themes
GET    /api/v1/modules
POST   /api/v1/modules/{module}/enable
GET    /api/v1/activitypub/actor/{user}
GET    /api/v1/feed/rss.xml
GET    /api/v1/feed/atom.xml
```

Authentification:

- sessions securisees pour l'admin;
- Laravel Sanctum pour tokens API personnels;
- OAuth2 optionnel via module;
- rate limiting par route, role et adresse IP.

## Administration

Filament sert de socle admin:

- resources pour pages, articles, medias, utilisateurs;
- panels separes si besoin (`admin`, `author`, `system`);
- widgets modules;
- actions bulk;
- audit log visible;
- permissions par action.

## Decentralisation et confidentialite

ActivityPub doit etre optionnel et desactivable. Quand actif:

- actor par site et par auteur;
- WebFinger;
- inbox/outbox;
- signature HTTP;
- moderation locale;
- blocage instances/domaines;
- queue obligatoire pour livraison federale.

Confidentialite:

- aucune telemetrie par defaut;
- verification de mises a jour opt-in;
- avatars locaux par defaut;
- proxy media distant optionnel;
- exports complets des donnees utilisateur.

## Mise a jour

Le coeur est mis a jour separement des modules:

- migrations idempotentes;
- sauvegarde automatique avant migration majeure;
- verrou de maintenance;
- verification compatibilite modules;
- rollback applicatif limite aux fichiers, pas aux migrations destructrices;
- canal stable uniquement par defaut.

Pour YunoHost, les scripts `upgrade` doivent:

- sauvegarder avant modification;
- mettre l'application en maintenance;
- installer dependances;
- lancer migrations;
- vider/rechauffer cache;
- restaurer le service.

