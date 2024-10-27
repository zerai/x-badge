<?php declare(strict_types=1);

namespace Badge\Core\Port\Driving;

use Badge\Core\Image;

interface CreateComposerLockBadge
{
    public function createComposerLockBadge(string $packageName): Image;
}
