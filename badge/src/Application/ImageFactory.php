<?php declare(strict_types=1);

namespace Badge\Application;

use Badge\Application\Domain\Model\RenderableValue;

interface ImageFactory
{
    public function createImageFromContext(RenderableValue $badgeContext): Image;

    public function createImageForDefaultBadge(): Image;
}
