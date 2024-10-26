<?php declare(strict_types=1);

namespace Badge\Application\Port\Driving;

use Badge\Application\Image;

interface CreateUnstableVersionBadge
{
    public function createUnstableVersionBadge(string $packageName): Image;
}
