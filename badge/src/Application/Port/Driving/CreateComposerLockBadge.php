<?php declare(strict_types=1);

namespace Badge\Application\Port\Driving;

use Badge\Application\Image;

interface CreateComposerLockBadge
{
    public function createComposerLockBadge(string $packageName): Image;
}
