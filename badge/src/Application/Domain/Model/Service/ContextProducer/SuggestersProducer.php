<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\Suggesters;
use Badge\Application\Port\Driven\ForReadingSuggesters;

final class SuggestersProducer implements ContextProducer
{
    private ForReadingSuggesters $suggestersReader;

    public function __construct(ForReadingSuggesters $suggestersReader)
    {
        $this->suggestersReader = $suggestersReader;
    }

    public function contextFor(string $packageName): BadgeContext
    {
        return Suggesters::withCount($this->suggestersReader->readSuggesters($packageName));
    }
}
