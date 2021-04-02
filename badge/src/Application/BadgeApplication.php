<?php declare(strict_types=1);

namespace Badge\Application;

use Badge\Application\PortIn\CreateSuggestersBadge;
use Badge\Application\PortIn\GetComposerLockBadge;

final class BadgeApplication implements BadgeApplicationInterface
{
    private GetComposerLockBadge $composerLockUseCase;

    private CreateSuggestersBadge $suggestersUseCase;

    public function __construct(
        GetComposerLockBadge $composerLockUseCase,
        CreateSuggestersBadge $suggestersUseCase
    ) {
        $this->composerLockUseCase = $composerLockUseCase;
        $this->suggestersUseCase = $suggestersUseCase;
    }

    public function getComposerLockBadge(string $packageName): Image
    {
        return $this->composerLockUseCase->getComposerLockBadge($packageName);
    }

    public function createSuggestersBadge(string $packageName): Image
    {
        return $this->suggestersUseCase->createSuggestersBadge($packageName);
    }
}
