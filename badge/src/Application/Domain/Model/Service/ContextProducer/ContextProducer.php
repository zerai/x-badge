<?php declare(strict_types=1);

namespace Badge\Application\Domain\Service\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext\BadgeContext;

interface ContextProducer
{
    public function contextFor(string $packageName): BadgeContext;
}
