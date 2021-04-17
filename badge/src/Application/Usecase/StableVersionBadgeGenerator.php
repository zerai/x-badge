<?php declare(strict_types=1);

namespace Badge\Application\Usecase;

use Badge\Application\Domain\Model\Service\ContextProducer\ContextProducer;
use Badge\Application\Image;
use Badge\Application\ImageFactory;
use Badge\Application\PortIn\CreateStableVersionBadge;

final class StableVersionBadgeGenerator implements CreateStableVersionBadge
{
    private ContextProducer $contextProducer;

    private ImageFactory $imageFactory;

    public function __construct(ContextProducer $suggestersContextProducer, ImageFactory $poserImageFactory)
    {
        $this->contextProducer = $suggestersContextProducer;
        $this->imageFactory = $poserImageFactory;
    }

    public function createStableVersionBadge(string $packageName): Image
    {
        try {
            $badgeContext = $this->contextProducer->contextFor($packageName);
        } catch (\Throwable $exception) {
            return $this->imageFactory->createImageForDefaultBadge();
        }

        return $this->imageFactory->createImageFromContext($badgeContext);
    }
}
