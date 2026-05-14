<?php

declare(strict_types=1);

return [
    'version' => env('LIBREPRESS_VERSION', '0.1.0'),

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
        'max_image_width' => 6000,
        'max_image_height' => 6000,
        'antivirus' => [
            'enabled' => (bool) env('LIBREPRESS_ANTIVIRUS_ENABLED', false),
            'binary' => env('LIBREPRESS_ANTIVIRUS_BINARY', 'clamscan'),
            'timeout' => (int) env('LIBREPRESS_ANTIVIRUS_TIMEOUT', 15),
        ],
    ],

    'comments' => [
        'blocked_words' => array_filter(explode(',', (string) env('LIBREPRESS_BLOCKED_COMMENT_WORDS', ''))),
    ],
];
