<?php declare(strict_types=1);

namespace Badge\Application\Port\Driving;

use Badge\Application\Image;

interface CreateDependentsBadge
{
    public function createDependentsBadge(string $packageName): Image;
}
