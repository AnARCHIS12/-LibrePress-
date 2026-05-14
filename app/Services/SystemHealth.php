<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

final class SystemHealth
{
    /**
     * @return array<string, array{status:string,detail:string}>
     */
    public function checks(): array
    {
        return [
            'database' => $this->database(),
            'storage' => $this->storage(),
            'cache' => $this->cache(),
            'queue' => [
                'status' => config('queue.default') === 'sync' ? 'warning' : 'ok',
                'detail' => 'driver: '.config('queue.default'),
            ],
        ];
    }

    /**
     * @return array{status:string,detail:string}
     */
    private function database(): array
    {
        try {
            DB::select('select 1');

            return ['status' => 'ok', 'detail' => config('database.default')];
        } catch (\Throwable $exception) {
            return ['status' => 'error', 'detail' => $exception->getMessage()];
        }
    }

    /**
     * @return array{status:string,detail:string}
     */
    private function storage(): array
    {
        return is_writable(storage_path())
            ? ['status' => 'ok', 'detail' => storage_path()]
            : ['status' => 'error', 'detail' => 'storage not writable'];
    }

    /**
     * @return array{status:string,detail:string}
     */
    private function cache(): array
    {
        try {
            cache()->put('healthcheck', 'ok', 5);

            return ['status' => cache()->get('healthcheck') === 'ok' ? 'ok' : 'warning', 'detail' => config('cache.default')];
        } catch (\Throwable $exception) {
            return ['status' => 'error', 'detail' => $exception->getMessage()];
        }
    }
}

