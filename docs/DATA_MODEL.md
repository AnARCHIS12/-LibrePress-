# Modele de donnees

## Tables coeur

### users

- `id`
- `name`
- `email`
- `password`
- `locale`
- `timezone`
- `status`
- `last_login_at`
- `created_at`
- `updated_at`

### roles / permissions / model_has_roles / model_has_permissions

Base recommandee: `spatie/laravel-permission`, avec conventions strictes:

- permissions prefixees par domaine;
- roles systeme non supprimables;
- cache permissions invalidable.

Roles initiaux:

- `super_admin`
- `admin`
- `editor`
- `author`
- `moderator`
- `viewer`

### contents

Table unifiee pour pages et articles.

- `id`
- `type`: `page`, `post`, `custom`
- `status`: `draft`, `review`, `scheduled`, `published`, `archived`
- `author_id`
- `parent_id`
- `slug`
- `title`
- `excerpt`
- `body_json`
- `body_html`
- `locale`
- `published_at`
- `scheduled_at`
- `meta`
- `created_at`
- `updated_at`

Index:

- unique `type + locale + slug`;
- index `status + published_at`;
- index `author_id`;
- full-text sur `title`, `excerpt`, `body_html` selon moteur.

### content_revisions

- `id`
- `content_id`
- `user_id`
- `title`
- `body_json`
- `body_html`
- `meta`
- `created_at`

### media

- `id`
- `disk`
- `path`
- `mime_type`
- `size`
- `width`
- `height`
- `alt`
- `caption`
- `hash`
- `created_by`
- `created_at`
- `updated_at`

### taxonomies / terms / content_terms

Modele flexible pour categories, tags et taxonomies modules.

### modules

- `id`
- `name`
- `slug`
- `version`
- `enabled`
- `manifest`
- `installed_at`
- `enabled_at`

### themes

- `id`
- `name`
- `slug`
- `version`
- `enabled`
- `config`

### settings

- `key`
- `value`
- `type`
- `scope`: `system`, `site`, `theme`, `module`
- `autoload`

### comments

Module officiel, mais table standard:

- `id`
- `content_id`
- `user_id`
- `author_name`
- `author_email_hash`
- `body`
- `status`
- `ip_hash`
- `user_agent_hash`
- `created_at`

### activitypub_*

Module officiel:

- `activitypub_actors`
- `activitypub_objects`
- `activitypub_followers`
- `activitypub_inbox`
- `activitypub_outbox`
- `activitypub_blocks`

## Multilingue

Approche recommandee: contenu par locale avec groupe de traduction.

```text
contents.translation_group_id
contents.locale
```

Cela evite les colonnes JSON peu indexables et permet des slugs differents par langue.

## Import/export WordPress

Importer officiel:

- WXR XML;
- medias via URL source avec file d'attente;
- auteurs mappables;
- categories/tags;
- redirections d'anciens slugs;
- blocs Gutenberg convertis vers schema LibrePress lorsque possible;
- HTML conserve en fallback.

Exporter:

- archive ZIP;
- `manifest.json`;
- contenus JSON;
- medias;
- themes/modules facultatifs;
- dump SQL optionnel chiffre.

