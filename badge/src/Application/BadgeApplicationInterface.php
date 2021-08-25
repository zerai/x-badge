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
