<?php declare(strict_types=1);

namespace Badge\Application;

use Badge\Application\PortIn\CreateDailyDownloadsBadge;
use Badge\Application\PortIn\CreateDependentsBadge;
use Badge\Application\PortIn\CreateMonthlyDownloadsBadge;
use Badge\Application\PortIn\CreateStableVersionBadge;
use Badge\Application\PortIn\CreateSuggestersBadge;
use Badge\Application\PortIn\CreateTotalDownloadsBadge;
use Badge\Application\PortIn\CreateUnstableVersionBadge;
use Badge\Application\PortIn\GetComposerLockBadge;

final class BadgeApplication implements BadgeApplicationInterface
{
    private GetComposerLockBadge $composerLockUseCase;

    private CreateSuggestersBadge $suggestersUseCase;

    private CreateDependentsBadge $dependentsUseCase;

    private CreateTotalDownloadsBadge $totalDownloadUseCase;

    private CreateMonthlyDownloadsBadge $monthlyDownloadsUseCase;

    private CreateDailyDownloadsBadge $dailyDownloadsUseCase;

    private CreateStableVersionBadge $stableVersionUseCase;

    private CreateUnstableVersionBadge $unstableVersionUseCase;

    public function __construct(
        GetComposerLockBadge $composerLockUseCase,
        CreateSuggestersBadge $suggestersUseCase,
        CreateDependentsBadge $dependentsUseCase,
        CreateTotalDownloadsBadge $totalDownloadUseCase,
        CreateMonthlyDownloadsBadge $monthlyDownloadsUseCase,
        CreateDailyDownloadsBadge $dailyDownloadsUseCase,
        CreateStableVersionBadge $stableVersionUseCase,
        CreateUnstableVersionBadge $unstableVersionUseCase
    ) {
        $this->composerLockUseCase = $composerLockUseCase;
        $this->suggestersUseCase = $suggestersUseCase;
        $this->dependentsUseCase = $dependentsUseCase;
        $this->totalDownloadUseCase = $totalDownloadUseCase;
        $this->monthlyDownloadsUseCase = $monthlyDownloadsUseCase;
        $this->dailyDownloadsUseCase = $dailyDownloadsUseCase;
        $this->stableVersionUseCase = $stableVersionUseCase;
        $this->unstableVersionUseCase = $unstableVersionUseCase;
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

    public function createMonthlyDownloadsBadge(string $packageName): Image
    {
        return $this->monthlyDownloadsUseCase->createMonthlyDownloadsBadge($packageName);
    }

    public function createDailyDownloadsBadge(string $packageName): Image
    {
        return $this->dailyDownloadsUseCase->createDailyDownloadsBadge($packageName);
    }

    public function createStableVersionBadge(string $packageName): Image
    {
        return $this->stableVersionUseCase->createStableVersionBadge($packageName);
    }

    public function CreateUnstableVersionBadge(string $packageName): Image
    {
        return $this->unstableVersionUseCase->CreateUnstableVersionBadge($packageName);
    }
}
