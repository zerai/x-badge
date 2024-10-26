<?php declare(strict_types=1);

namespace Badge\Application\Usecase;

use Badge\Application\Domain\Model\Service\ContextProducer\ContextProducer;
use Badge\Application\Image;
use Badge\Application\ImageFactory;
use Badge\Application\Port\Driving\CreateGitattributesBadge;

final class GitattributesBadgeGenerator implements CreateGitattributesBadge
{
    private ContextProducer $contextProducer;

    private ImageFactory $imageFactory;

    public function __construct(ContextProducer $contextProducer, ImageFactory $imageFactory)
    {
        $this->contextProducer = $contextProducer;
        $this->imageFactory = $imageFactory;
    }

    public function createGitattributesBadge(string $packageName): Image
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
