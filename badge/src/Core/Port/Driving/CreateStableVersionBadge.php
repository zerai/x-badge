<?php declare(strict_types=1);

namespace Badge\Core\Port\Driving;

use Badge\Core\Image;

interface CreateStableVersionBadge
{
    public function createStableVersionBadge(string $packageName): Image;
}
