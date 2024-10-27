<?php declare(strict_types=1);

namespace Badge\Core\Port\Driving;

use Badge\Core\Image;

interface CreateSuggestersBadge
{
    public function createSuggestersBadge(string $packageName): Image;
}
