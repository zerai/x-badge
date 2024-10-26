<?php declare(strict_types=1);

namespace Badge\Application\Port\Driving;

use Badge\Application\Image;

interface CreateTotalDownloadsBadge
{
    public function createTotalDownloadsBadge(string $packageName): Image;
}
