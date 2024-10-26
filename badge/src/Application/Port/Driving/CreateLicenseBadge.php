<?php declare(strict_types=1);

namespace Badge\Application\Port\Driving;

use Badge\Application\Image;

interface CreateLicenseBadge
{
    public function createLicenseBadge(string $packageName): Image;
}
