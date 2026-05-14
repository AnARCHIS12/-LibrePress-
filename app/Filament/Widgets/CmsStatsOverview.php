<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Comment;
use App\Models\Content;
use App\Models\Media;
use App\Models\Module;
use App\Models\Theme;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class CmsStatsOverview extends StatsOverviewWidget
{
    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make('Contenus publies', Content::query()->where('status', 'published')->count())
                ->description(Content::query()->where('status', 'draft')->count().' brouillons')
                ->color('success'),
            Stat::make('Commentaires en attente', Comment::query()->where('status', 'pending')->count())
                ->description(Comment::query()->where('is_spam', true)->count().' spam')
                ->color('warning'),
            Stat::make('Medias', Media::query()->count())
                ->description((string) $this->humanBytes((int) Media::query()->sum('size')))
                ->color('info'),
            Stat::make('Utilisateurs', User::query()->count())
                ->description(User::query()->where('status', 'active')->count().' actifs')
                ->color('gray'),
            Stat::make('Modules actifs', Module::query()->where('enabled', true)->count())
                ->description(Module::query()->count().' installes')
                ->color('primary'),
            Stat::make('Theme actif', Theme::query()->where('enabled', true)->value('name') ?? 'Aucun')
                ->description('Themes installes: '.Theme::query()->count())
                ->color('primary'),
        ];
    }

    private function humanBytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes.' o';
        }

        if ($bytes < 1048576) {
            return round($bytes / 1024, 1).' Ko';
        }

        return round($bytes / 1048576, 1).' Mo';
    }
}
