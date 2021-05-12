<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\Suggesters;

final class SuggestersProducer implements ContextProducer
{
    private SuggestersReader $suggestersReader;

    public function __construct(SuggestersReader $suggestersReader)
    {
        $this->suggestersReader = $suggestersReader;
    }

    public function contextFor(string $packageName): BadgeContext
    {
        return Suggesters::withCount($this->suggestersReader->readSuggesters($packageName));
    }
}
