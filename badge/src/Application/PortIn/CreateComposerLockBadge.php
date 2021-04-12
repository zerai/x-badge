<?php declare(strict_types=1);

namespace Badge\Application\PortIn;

use Badge\Application\Image;

interface CreateComposerLockBadge
{
    public function createComposerLockBadge(string $packageName): Image;
}
