<?php declare(strict_types=1);

namespace Badge\Application\Port\Driving;

use Badge\Application\Image;

interface CreateDailyDownloadsBadge
{
    public function createDailyDownloadsBadge(string $packageName): Image;
}
