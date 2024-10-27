<?php declare(strict_types=1);

namespace Badge\Core\Domain\Model\Service\ContextProducer;

use Badge\Core\Domain\Model\BadgeContext;

interface ContextProducer
{
    public function contextFor(string $packageName): BadgeContext;
}
