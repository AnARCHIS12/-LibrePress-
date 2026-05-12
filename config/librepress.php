<?php

declare(strict_types=1);

return [
    'modules_path' => env('LIBREPRESS_MODULES_PATH', base_path('modules')),
    'themes_path' => env('LIBREPRESS_THEMES_PATH', base_path('themes')),

    'privacy' => [
        'update_checks' => (bool) env('LIBREPRESS_UPDATE_CHECKS', false),
        'activitypub_enabled' => (bool) env('LIBREPRESS_ACTIVITYPUB_ENABLED', false),
        'telemetry_enabled' => false,
    ],

    'cache' => [
        'public_pages_ttl' => 300,
        'fragments_ttl' => 900,
        'permissions_ttl' => 3600,
    ],

    'security' => [
        'max_upload_size_mb' => 32,
        'allowed_upload_mimes' => [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/avif',
            'application/pdf',
        ],
    ],
];

