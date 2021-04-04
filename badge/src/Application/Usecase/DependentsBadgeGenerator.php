<?php declare(strict_types=1);

namespace Badge\Application\Usecase;

use Badge\Application\Domain\Model\Service\ContextProducer\ContextProducer;
use Badge\Application\Image;
use Badge\Application\ImageFactory;
use Badge\Application\PortIn\CreateDependentsBadge;

final class DependentsBadgeGenerator implements CreateDependentsBadge
{
    private ContextProducer $contextProducer;

    private ImageFactory $imageFactory;

    public function __construct(ContextProducer $dependentsContextProducer, ImageFactory $poserImageFactory)
    {
        $this->contextProducer = $dependentsContextProducer;
        $this->imageFactory = $poserImageFactory;
    }

    public function createDependentsBadge(string $packageName): Image
    {
        try {
            $badgeContext = $this->contextProducer->contextFor($packageName);
        } catch (\Exception $exception) {
            return $this->imageFactory->createImageForDefaultBadge();
        }

        return $this->imageFactory->createImageFromContext($badgeContext);
    }
}
