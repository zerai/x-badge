<?php declare(strict_types=1);

namespace Badge\Core;

use Badge\Core\Port\Driving\CreateComposerLockBadge;
use Badge\Core\Port\Driving\CreateDailyDownloadsBadge;
use Badge\Core\Port\Driving\CreateDependentsBadge;
use Badge\Core\Port\Driving\CreateGitattributesBadge;
use Badge\Core\Port\Driving\CreateLicenseBadge;
use Badge\Core\Port\Driving\CreateMonthlyDownloadsBadge;
use Badge\Core\Port\Driving\CreateStableVersionBadge;
use Badge\Core\Port\Driving\CreateSuggestersBadge;
use Badge\Core\Port\Driving\CreateTotalDownloadsBadge;
use Badge\Core\Port\Driving\CreateUnstableVersionBadge;

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
