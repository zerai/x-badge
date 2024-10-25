<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\StableVersion;

final class StableVersionProducer implements ContextProducer
{
    private ForReadingStableVersion $stableVersionReader;

    public function __construct(ForReadingStableVersion $stableVersionReader)
    {
        $this->stableVersionReader = $stableVersionReader;
    }

    public function contextFor(string $packageName): BadgeContext
    {
        return StableVersion::fromString($this->stableVersionReader->readStableVersion($packageName));
    }
}
