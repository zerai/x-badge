<?php declare(strict_types=1);

namespace Badge\Core\Usecase;

use Badge\Core\Domain\Model\Service\ContextProducer\ContextProducer;
use Badge\Core\Image;
use Badge\Core\ImageFactory;
use Badge\Core\Port\Driving\CreateTotalDownloadsBadge;

final class TotalDownloadsBadgeGenerator implements CreateTotalDownloadsBadge
{
    private ContextProducer $contextProducer;

    private ImageFactory $imageFactory;

    public function __construct(ContextProducer $dependentsContextProducer, ImageFactory $poserImageFactory)
    {
        $this->contextProducer = $dependentsContextProducer;
        $this->imageFactory = $poserImageFactory;
    }

    public function createTotalDownloadsBadge(string $packageName): Image
    {
        try {
            $badgeContext = $this->contextProducer->contextFor($packageName);
        } catch (\Exception $exception) {
            return $this->imageFactory->createImageForDefaultBadge();
        }

        return $this->imageFactory->createImageFromContext($badgeContext);
    }
}
