<?php declare(strict_types=1);

namespace Badge\Core\Usecase;

use Badge\Core\Domain\Model\Service\ContextProducer\ContextProducer;
use Badge\Core\Image;
use Badge\Core\ImageFactory;
use Badge\Core\Port\Driving\CreateMonthlyDownloadsBadge;

final class MonthlyDownloadsBadgeGenerator implements CreateMonthlyDownloadsBadge
{
    private ContextProducer $contextProducer;

    private ImageFactory $imageFactory;

    public function __construct(ContextProducer $monthlyContextProducer, ImageFactory $poserImageFactory)
    {
        $this->contextProducer = $monthlyContextProducer;
        $this->imageFactory = $poserImageFactory;
    }

    public function createMonthlyDownloadsBadge(string $packageName): Image
    {
        try {
            $badgeContext = $this->contextProducer->contextFor($packageName);
        } catch (\Exception $exception) {
            return $this->imageFactory->createImageForDefaultBadge();
        }

        return $this->imageFactory->createImageFromContext($badgeContext);
    }
}
