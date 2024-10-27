<?php declare(strict_types=1);

namespace Badge\Core\Domain\Model\Service\ContextProducer;

use Badge\Core\Domain\Model\BadgeContext;
use Badge\Core\Domain\Model\ContextValue\Suggesters;
use Badge\Core\Port\Driven\ForReadingBadgeContextValues\ForReadingSuggesters;

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
