<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\Version;

final class StableVersionProducer implements ContextProducer
{
    /**
     * @var StableVersionReader
     */
    private $stableVersionReader;

    public function __construct(StableVersionReader $stableVersionReader)
    {
        $this->stableVersionReader = $stableVersionReader;
    }

    public function contextFor(string $packageName): BadgeContext
    {
        try {
            return Version::fromString($this->stableVersionReader->readStableVersion($packageName));
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
