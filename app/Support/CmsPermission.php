<?php

declare(strict_types=1);

namespace App\Support;

final class CmsPermission
{
    public const ADMIN_ACCESS = 'admin.access';
    public const CONTENT_VIEW = 'content.view';
    public const CONTENT_CREATE = 'content.create';
    public const CONTENT_UPDATE = 'content.update';
    public const CONTENT_DELETE = 'content.delete';
    public const CONTENT_PUBLISH = 'content.publish';
    public const MEDIA_VIEW = 'media.view';
    public const MEDIA_UPLOAD = 'media.upload';
    public const MEDIA_DELETE = 'media.delete';
    public const COMMENTS_MODERATE = 'comments.moderate';
    public const SETTINGS_MANAGE = 'settings.manage';
    public const MODULES_MANAGE = 'modules.manage';
    public const THEMES_MANAGE = 'themes.manage';
    public const USERS_MANAGE = 'users.manage';

    /**
     * @return list<string>
     */
    public static function all(): array
    {
        return [
            self::ADMIN_ACCESS,
            self::CONTENT_VIEW,
            self::CONTENT_CREATE,
            self::CONTENT_UPDATE,
            self::CONTENT_DELETE,
            self::CONTENT_PUBLISH,
            self::MEDIA_VIEW,
            self::MEDIA_UPLOAD,
            self::MEDIA_DELETE,
            self::COMMENTS_MODERATE,
            self::SETTINGS_MANAGE,
            self::MODULES_MANAGE,
            self::THEMES_MANAGE,
            self::USERS_MANAGE,
        ];
    }
}

