<?php

declare(strict_types=1);

namespace App\Providers;

use App\Core\Contracts\HookRegistryInterface;
use App\Core\Hooks\InMemoryHookRegistry;
use App\Rules\RequiredIfFileImage;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

final class LibrePressServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(HookRegistryInterface::class, InMemoryHookRegistry::class);
    }

    public function boot(): void
    {
        $moduleMigrationPaths = glob(base_path('modules/*/database/migrations')) ?: [];

        if ($moduleMigrationPaths !== []) {
            $this->loadMigrationsFrom($moduleMigrationPaths);
        }

        RateLimiter::for('login', fn (Request $request) => [
            Limit::perMinute(5)->by((string) $request->ip()),
        ]);

        RateLimiter::for('comments', fn (Request $request) => [
            Limit::perMinute(3)->by((string) $request->ip()),
        ]);

        Validator::extendImplicit('required_if_file_image', function (string $attribute, mixed $value, array $parameters): bool {
            $rule = new RequiredIfFileImage($parameters[0] ?? 'file');
            $failed = false;
            $rule->validate($attribute, $value, function () use (&$failed): void {
                $failed = true;
            });

            return ! $failed;
        });
    }
}
