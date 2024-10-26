<?php declare(strict_types=1);

namespace Badge\Application\Port\Driving;

use Badge\Application\Image;

interface CreateStableVersionBadge
{
    public function createStableVersionBadge(string $packageName): Image;
}
