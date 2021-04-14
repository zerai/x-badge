<?php declare(strict_types=1);

namespace Badge\Application\PortIn;

use Badge\Application\Image;

interface CreateDailyDownloadsBadge
{
    public function createDailyDownloadsBadge(string $packageName): Image;
}
