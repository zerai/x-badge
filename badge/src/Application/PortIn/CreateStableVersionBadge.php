<?php declare(strict_types=1);

namespace Badge\Application\PortIn;

use Badge\Application\Image;

interface CreateStableVersionBadge
{
    public function createStableVersionBadge(string $packageName): Image;
}
