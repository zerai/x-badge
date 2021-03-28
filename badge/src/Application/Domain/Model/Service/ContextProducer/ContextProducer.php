<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext\BadgeContext;
use Badge\Application\Domain\Model\RenderableValue;

interface ContextProducer
{
    //public function contextFor(string $packageName): BadgeContext;
    public function contextFor(string $packageName): RenderableValue;
}
