<?php declare(strict_types=1);

namespace Badge\Application;

use Badge\Application\PortIn\CreateComposerLockBadge;
use Badge\Application\PortIn\CreateDailyDownloadsBadge;
use Badge\Application\PortIn\CreateDependentsBadge;
use Badge\Application\PortIn\CreateGitattributesBadge;
use Badge\Application\PortIn\CreateLicenseBadge;
use Badge\Application\PortIn\CreateMonthlyDownloadsBadge;
use Badge\Application\PortIn\CreateStableVersionBadge;
use Badge\Application\PortIn\CreateSuggestersBadge;
use Badge\Application\PortIn\CreateTotalDownloadsBadge;
use Badge\Application\PortIn\CreateUnstableVersionBadge;

final class BadgeApplication implements BadgeApplicationInterface
{
    private CreateComposerLockBadge $composerLockUseCase;

    private CreateGitattributesBadge $gitattributesUseCase;

    private CreateSuggestersBadge $suggestersUseCase;

    private CreateDependentsBadge $dependentsUseCase;

    private CreateTotalDownloadsBadge $totalDownloadUseCase;

    private CreateMonthlyDownloadsBadge $monthlyDownloadsUseCase;

    private CreateDailyDownloadsBadge $dailyDownloadsUseCase;

    private CreateStableVersionBadge $stableVersionUseCase;

    private CreateUnstableVersionBadge $unstableVersionUseCase;

    private CreateLicenseBadge $licenseUseCase;

    public function __construct(
        CreateComposerLockBadge $composerLockUseCase,
        CreateGitattributesBadge $gitattributesUseCase,
        CreateSuggestersBadge $suggestersUseCase,
        CreateDependentsBadge $dependentsUseCase,
        CreateTotalDownloadsBadge $totalDownloadUseCase,
        CreateMonthlyDownloadsBadge $monthlyDownloadsUseCase,
        CreateDailyDownloadsBadge $dailyDownloadsUseCase,
        CreateStableVersionBadge $stableVersionUseCase,
        CreateUnstableVersionBadge $unstableVersionUseCase,
        CreateLicenseBadge $licenseUseCase
    ) {
        $this->composerLockUseCase = $composerLockUseCase;
        $this->gitattributesUseCase = $gitattributesUseCase;
        $this->suggestersUseCase = $suggestersUseCase;
        $this->dependentsUseCase = $dependentsUseCase;
        $this->totalDownloadUseCase = $totalDownloadUseCase;
        $this->monthlyDownloadsUseCase = $monthlyDownloadsUseCase;
        $this->dailyDownloadsUseCase = $dailyDownloadsUseCase;
        $this->stableVersionUseCase = $stableVersionUseCase;
        $this->unstableVersionUseCase = $unstableVersionUseCase;
        $this->licenseUseCase = $licenseUseCase;
    }

    public function createComposerLockBadge(string $packageName): Image
    {
        return $this->composerLockUseCase->createComposerLockBadge($packageName);
    }

    public function createGitattributesBadge(string $packageName): Image
    {
        return $this->gitattributesUseCase->createGitattributesBadge($packageName);
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

    public function createLicenseBadge(string $packageName): Image
    {
        return $this->licenseUseCase->createLicenseBadge($packageName);
    }
}
