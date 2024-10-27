<?php declare(strict_types=1);

namespace Badge\Core\Domain\Model\Service\ContextProducer;

use Badge\Core\Domain\Model\BadgeContext;
use Badge\Core\Domain\Model\ContextValue\StableVersion;
use Badge\Core\Port\Driven\ForReadingBadgeContextValues\ForReadingStableVersion;

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
