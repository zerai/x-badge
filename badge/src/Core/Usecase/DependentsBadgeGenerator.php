<?php declare(strict_types=1);

namespace Badge\Core\Usecase;

use Badge\Core\Domain\Model\Service\ContextProducer\ContextProducer;
use Badge\Core\Image;
use Badge\Core\ImageFactory;
use Badge\Core\Port\Driving\CreateDependentsBadge;

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
