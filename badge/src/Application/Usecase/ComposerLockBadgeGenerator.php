<?php declare(strict_types=1);

namespace Badge\Application\Usecase;

use Badge\Application\Domain\Model\Service\ContextProducer\ContextProducer;
use Badge\Application\Image;
use Badge\Application\ImageFactory;
use Badge\Application\PortIn\GetComposerLockBadge;

final class ComposerLockBadgeGenerator implements GetComposerLockBadge
{
    private ContextProducer $contextProducer;

    private ImageFactory $imageFactory;

    public function __construct(ContextProducer $contextProducer, ImageFactory $imageFactory)
    {
        $this->contextProducer = $contextProducer;
        $this->imageFactory = $imageFactory;
    }

    public function getComposerLockBadge(string $packageName): Image
    {
        try {
            $badgeImage = $this->imageFactory->createImageFromContext(
                $this->contextProducer->contextFor($packageName)
            );
        } catch (\Throwable $th) {
            $badgeImage = $this->imageFactory->createImageForDefaultBadge();
        }

        return $badgeImage;
    }
}
