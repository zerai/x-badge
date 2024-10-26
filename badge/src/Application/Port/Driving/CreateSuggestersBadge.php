<?php declare(strict_types=1);

namespace Badge\Application\Port\Driving;

use Badge\Application\Image;

interface CreateSuggestersBadge
{
    public function createSuggestersBadge(string $packageName): Image;
}
