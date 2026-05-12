<?php

declare(strict_types=1);

namespace App\Core\Contracts;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Routing\Registrar;

final readonly class ModuleContext
{
    public function __construct(
        public HookRegistryInterface $hooks,
        public PermissionRegistrarInterface $permissions,
        public Registrar $router,
        public Dispatcher $events,
    ) {
    }
}

