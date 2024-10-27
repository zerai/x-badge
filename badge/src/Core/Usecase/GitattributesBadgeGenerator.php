<?php declare(strict_types=1);

namespace Badge\Core\Usecase;

use Badge\Core\Domain\Model\Service\ContextProducer\ContextProducer;
use Badge\Core\Image;
use Badge\Core\ImageFactory;
use Badge\Core\Port\Driving\CreateGitattributesBadge;

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
