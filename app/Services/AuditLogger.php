<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

final class AuditLogger
{
    /**
     * @param array<string, mixed> $properties
     */
    public function log(Request $request, string $action, ?Model $subject = null, array $properties = []): void
    {
        $logger = activity('admin')
            ->causedBy($request->user())
            ->withProperties([
                ...$properties,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

        if ($subject) {
            $logger->performedOn($subject);
        }

        $logger->log($action);
    }
}
