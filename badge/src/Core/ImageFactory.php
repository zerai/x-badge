<?php declare(strict_types=1);

namespace Badge\Core;

use Badge\Core\Domain\Model\BadgeContext;

interface ImageFactory
{
    public function createImageFromContext(BadgeContext $badgeContext): Image;

    public function createImageForDefaultBadge(): Image;
}
