<?php declare(strict_types=1);

namespace Badge\Core\Domain\Model\Service\ContextProducer;

use Badge\Core\Domain\Model\BadgeContext;
use Badge\Core\Domain\Model\ContextValue\UnstableVersion;
use Badge\Core\Port\Driven\ForReadingBadgeContextValues\ForReadingUnstableVersion;

final class UnstableVersionProducer implements ContextProducer
{
    private ForReadingUnstableVersion $unstableVersionReader;

    public function __construct(ForReadingUnstableVersion $unstableVersionReader)
    {
        $this->unstableVersionReader = $unstableVersionReader;
    }

    public function contextFor(string $packageName): BadgeContext
    {
        return UnstableVersion::fromString($this->unstableVersionReader->readUnstableVersion($packageName));
    }
}
