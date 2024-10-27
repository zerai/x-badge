<?php declare(strict_types=1);

namespace Badge\Core\Port\Driving;

use Badge\Core\Image;

interface CreateMonthlyDownloadsBadge
{
    public function createMonthlyDownloadsBadge(string $packageName): Image;
}
