<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\Suggesters;

final class SuggestersProducer implements ContextProducer
{
    /**
     * @var SuggestersReader
     */
    private $suggestersReader;

    public function __construct(SuggestersReader $suggestersReader)
    {
        $this->suggestersReader = $suggestersReader;
    }

    public function contextFor(string $packageName): BadgeContext
    {
        try {
            return new Suggesters($this->suggestersReader->readSuggesters($packageName));
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
