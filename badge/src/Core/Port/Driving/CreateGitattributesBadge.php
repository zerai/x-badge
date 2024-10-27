<?php declare(strict_types=1);

namespace Badge\Core\Port\Driving;

use Badge\Core\Image;

interface CreateGitattributesBadge
{
    public function createGitattributesBadge(string $packageName): Image;
}
