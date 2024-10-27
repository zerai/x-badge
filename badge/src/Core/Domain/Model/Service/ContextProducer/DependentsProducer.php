<?php declare(strict_types=1);

namespace Badge\Core\Domain\Model\Service\ContextProducer;

use Badge\Core\Domain\Model\BadgeContext;
use Badge\Core\Domain\Model\ContextValue\Dependents;
use Badge\Core\Port\Driven\ForReadingBadgeContextValues\ForReadingDependents;

final class DependentsProducer implements ContextProducer
{
    private ForReadingDependents $dependentsReader;

    public function __construct(ForReadingDependents $dependentsReader)
    {
        $this->dependentsReader = $dependentsReader;
    }

    public function contextFor(string $packageName): BadgeContext
    {
        return Dependents::withCount($this->dependentsReader->readDependents($packageName));
    }
}
