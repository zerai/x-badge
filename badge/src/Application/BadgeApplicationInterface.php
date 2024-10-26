<?php declare(strict_types=1);

namespace Badge\Application;

use Badge\Application\Port\Driving\CreateComposerLockBadge;
use Badge\Application\Port\Driving\CreateDailyDownloadsBadge;
use Badge\Application\Port\Driving\CreateDependentsBadge;
use Badge\Application\Port\Driving\CreateGitattributesBadge;
use Badge\Application\Port\Driving\CreateLicenseBadge;
use Badge\Application\Port\Driving\CreateMonthlyDownloadsBadge;
use Badge\Application\Port\Driving\CreateStableVersionBadge;
use Badge\Application\Port\Driving\CreateSuggestersBadge;
use Badge\Application\Port\Driving\CreateTotalDownloadsBadge;
use Badge\Application\Port\Driving\CreateUnstableVersionBadge;

interface BadgeApplicationInterface extends
    CreateComposerLockBadge,
    CreateGitattributesBadge,
    CreateSuggestersBadge,
    CreateDependentsBadge,
    CreateTotalDownloadsBadge,
    CreateMonthlyDownloadsBadge,
    CreateDailyDownloadsBadge,
    CreateStableVersionBadge,
    CreateUnstableVersionBadge,
    CreateLicenseBadge
{
}
