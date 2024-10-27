<?php declare(strict_types=1);

namespace Badge\Core\Port\Driving;

use Badge\Core\Image;

interface CreateUnstableVersionBadge
{
    public function createUnstableVersionBadge(string $packageName): Image;
}
