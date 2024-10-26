<?php declare(strict_types=1);

namespace Badge\Application\Usecase;

use Badge\Application\Domain\Model\Service\ContextProducer\ContextProducer;
use Badge\Application\Image;
use Badge\Application\ImageFactory;
use Badge\Application\Port\Driving\CreateMonthlyDownloadsBadge;

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
