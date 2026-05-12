# Securite et performance

## Securite

Principes:

- typage strict PHP;
- validation par FormRequest/DTO;
- policies Laravel pour chaque action sensible;
- permissions fines avec cache;
- CSP stricte en admin;
- upload media avec liste blanche MIME;
- scan dimension/taille image avant traitement;
- sanitization HTML cote serveur;
- rate limiting API, login, commentaires et ActivityPub;
- audit log pour actions admin;
- secrets uniquement via `.env`;
- pas de telemetrie par defaut.

Protections essentielles:

- `SameSite=Lax` ou `Strict` pour cookies admin;
- 2FA optionnelle pour administrateurs;
- sessions invalidees apres changement mot de passe;
- tokens API haches;
- prevention SSRF pour import media distant;
- moderation et blocage de domaines fediverse;
- permissions modules declarees explicitement.

## Isolation plugins

PHP ne permet pas un sandboxing parfait dans le meme process. La strategie realiste:

- marketplace officielle signee;
- manifestes stricts;
- permissions declarees;
- revue statique avant installation distante;
- interdiction d'ecrire dans `app/`, `config/`, `routes/` depuis un module;
- migrations limitees au namespace module;
- hooks types;
- desactivation d'urgence via base/env.

Pour isolation forte, prevoir plus tard des workers separes pour modules non fiables.

## Performance

Optimisations par defaut:

- cache config/routes/views Laravel;
- cache page public pour visiteurs anonymes;
- cache fragments pour menus, taxonomies, widgets;
- Redis pour cache/queue;
- images converties en WebP/AVIF lorsque possible;
- lazy loading images;
- index SQL explicites;
- pagination cursor pour API;
- jobs pour federation, import, thumbnails, sauvegardes;
- Octane optionnel mais pas requis sur petits VPS.

## Petits VPS et Raspberry Pi

Profil minimal:

- PHP-FPM 2 a 4 workers;
- Redis limite en memoire;
- MariaDB avec buffer pool ajuste;
- thumbnails generes en queue lente;
- ActivityPub livraison batch;
- cache HTML disque possible si Redis absent;
- desactiver recherche avancee au profit SQL full-text;
- cron toutes les 5 minutes.

Services optionnels:

- Meilisearch uniquement si RAM suffisante;
- S3/MinIO seulement si besoin;
- queue dediee seulement au-dela d'un petit site.

## Scaling

Niveau 1: VPS unique.

- Nginx + PHP-FPM + DB + Redis.

Niveau 2: separation workers.

- web;
- queue;
- scheduler;
- DB manuelle/managed;
- Redis dedie.

Niveau 3: multi-instance.

- stockage objet pour medias;
- sessions Redis;
- cache Redis;
- CDN optionnel;
- jobs ActivityPub dedies;
- read replicas pour gros sites.

## Sauvegardes

Sauvegarde applicative:

- dump DB;
- archive medias;
- `.env` chiffre;
- manifestes modules/themes;
- version coeur.

Frequence:

- quotidienne sur petits sites;
- avant chaque mise a jour;
- retention configurable;
- restauration testable depuis l'admin et CLI.

