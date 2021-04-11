<?php declare(strict_types=1);

namespace Badge\Application\PortIn;

use Badge\Application\Image;

interface CreateVersionBadge
{
    public function createVersionBadge(string $packageName): Image;
}
