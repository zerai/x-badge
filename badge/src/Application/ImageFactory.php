<?php declare(strict_types=1);

namespace Badge\Application;

use Badge\Application\Domain\Model\BadgeContext;

interface ImageFactory
{
    public function createImageFromContext(BadgeContext $badgeContext): Image;

    public function createImageForDefaultBadge(): Image;
}
