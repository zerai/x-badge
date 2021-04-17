<?php declare(strict_types=1);

namespace Badge\Application\Usecase;

use Badge\Application\Domain\Model\Service\ContextProducer\ContextProducer;
use Badge\Application\Image;
use Badge\Application\ImageFactory;
use Badge\Application\PortIn\CreateComposerLockBadge;

final class ComposerLockBadgeGenerator implements CreateComposerLockBadge
{
    private ContextProducer $contextProducer;

    private ImageFactory $imageFactory;

    public function __construct(ContextProducer $contextProducer, ImageFactory $imageFactory)
    {
        $this->contextProducer = $contextProducer;
        $this->imageFactory = $imageFactory;
    }

    public function createComposerLockBadge(string $packageName): Image
    {
        try {
            $badgeImage = $this->imageFactory->createImageFromContext(
                $this->contextProducer->contextFor($packageName)
            );
        } catch (\Throwable $exception) {
            $badgeImage = $this->imageFactory->createImageForDefaultBadge();
        }

        return $badgeImage;
    }
}
