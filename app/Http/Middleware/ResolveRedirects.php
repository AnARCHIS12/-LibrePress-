<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ResolveRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET')) {
            $path = '/'.ltrim($request->path(), '/');
            $redirect = Redirect::query()->where('source_path', $path)->first();

            if ($redirect) {
                return redirect($redirect->target_path, $redirect->status_code);
            }
        }

        return $next($request);
    }
}

