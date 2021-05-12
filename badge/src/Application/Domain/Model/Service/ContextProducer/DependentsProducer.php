<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\Dependents;

final class DependentsProducer implements ContextProducer
{
    private DependentsReader $dependentsReader;

    public function __construct(DependentsReader $dependentsReader)
    {
        $this->dependentsReader = $dependentsReader;
    }

    public function contextFor(string $packageName): BadgeContext
    {
        return Dependents::withCount($this->dependentsReader->readDependents($packageName));
    }
}
