<?php declare(strict_types=1);

namespace Badge\Application\Port\Driving;

use Badge\Application\Image;

interface CreateMonthlyDownloadsBadge
{
    public function createMonthlyDownloadsBadge(string $packageName): Image;
}
