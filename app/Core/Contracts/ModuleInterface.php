<?php

declare(strict_types=1);

namespace App\Core\Contracts;

interface ModuleInterface
{
    public function slug(): string;

    public function version(): string;

    public function boot(ModuleContext $context): void;

    public function enable(ModuleContext $context): void;

    public function disable(ModuleContext $context): void;
}

