<?php declare(strict_types=1);

namespace Badge\Core\Port\Driving;

use Badge\Core\Image;

interface CreateDependentsBadge
{
    public function createDependentsBadge(string $packageName): Image;
}