<?php declare(strict_types=1);

namespace Badge\Application\PortIn;

use Badge\Application\Image;

interface CreateMonthlyDownloadsBadge
{
    public function createMonthlyDownloadsBadge(string $packageName): Image;
}
