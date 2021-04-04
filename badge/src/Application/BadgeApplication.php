<?php declare(strict_types=1);

namespace Badge\Application;

use Badge\Application\PortIn\CreateDependentsBadge;
use Badge\Application\PortIn\CreateSuggestersBadge;
use Badge\Application\PortIn\CreateTotalDownloadsBadge;
use Badge\Application\PortIn\GetComposerLockBadge;

final class BadgeApplication implements BadgeApplicationInterface
{
    private GetComposerLockBadge $composerLockUseCase;

    private CreateSuggestersBadge $suggestersUseCase;

    private CreateDependentsBadge $dependentsUseCase;

    private CreateTotalDownloadsBadge $totalDownloadUseCase;

    public function __construct(
        GetComposerLockBadge $composerLockUseCase,
        CreateSuggestersBadge $suggestersUseCase,
        CreateDependentsBadge $dependentsUseCase,
        CreateTotalDownloadsBadge $totalDownloadUseCase
    ) {
        $this->composerLockUseCase = $composerLockUseCase;
        $this->suggestersUseCase = $suggestersUseCase;
        $this->dependentsUseCase = $dependentsUseCase;
        $this->totalDownloadUseCase = $totalDownloadUseCase;
    }

    public function getComposerLockBadge(string $packageName): Image
    {
        return $this->composerLockUseCase->getComposerLockBadge($packageName);
    }

    public function createSuggestersBadge(string $packageName): Image
    {
        return $this->suggestersUseCase->createSuggestersBadge($packageName);
    }

    public function createDependentsBadge(string $packageName): Image
    {
        return $this->dependentsUseCase->createDependentsBadge($packageName);
    }

    public function createTotalDownloadsBadge(string $packageName): Image
    {
        return $this->totalDownloadUseCase->createTotalDownloadsBadge($packageName);
    }
}
