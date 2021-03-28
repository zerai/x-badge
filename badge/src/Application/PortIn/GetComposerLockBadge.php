<?php declare(strict_types=1);

namespace Badge\Application\PortIn;

use Badge\Application\Image;

interface GetComposerLockBadge
{
    public function getComposerLockBadge(string $packageName): Image;
}