<?php declare(strict_types=1);

namespace Badge\Application\Port\Driving;

use Badge\Application\Image;

interface CreateGitattributesBadge
{
    public function createGitattributesBadge(string $packageName): Image;
}
