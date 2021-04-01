<?php declare(strict_types=1);

namespace Badge\Application;

use Badge\Application\PortIn\GetComposerLockBadge;

final class BadgeApplication implements BadgeApplicationInterface
{
    private GetComposerLockBadge $composerLockUseCase;

    public function __construct(
        GetComposerLockBadge $composerLockUseCase
    ) {
        $this->composerLockUseCase = $composerLockUseCase;
    }

    public function getComposerLockBadge(string $packageName): Image
    {
        return $this->composerLockUseCase->getComposerLockBadge($packageName);
    }
}
