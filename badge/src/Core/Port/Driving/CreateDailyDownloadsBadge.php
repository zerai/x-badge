<?php declare(strict_types=1);

namespace Badge\Core\Port\Driving;

use Badge\Core\Image;

interface CreateDailyDownloadsBadge
{
    public function createDailyDownloadsBadge(string $packageName): Image;
}
