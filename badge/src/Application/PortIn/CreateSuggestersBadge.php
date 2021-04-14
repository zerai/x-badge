<?php declare(strict_types=1);

namespace Badge\Application\PortIn;

use Badge\Application\Image;

interface CreateSuggestersBadge
{
    public function createSuggestersBadge(string $packageName): Image;
}
