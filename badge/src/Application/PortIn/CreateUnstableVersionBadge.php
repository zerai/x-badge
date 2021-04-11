<?php declare(strict_types=1);

namespace Badge\Application\PortIn;

use Badge\Application\Image;

interface CreateUnstableVersionBadge
{
    public function CreateUnstableVersionBadge(string $packageName): Image;
}
