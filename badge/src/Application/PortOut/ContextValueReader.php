<?php declare(strict_types=1);

namespace Badge\Application\PortOut;

use Badge\Application\Port\Driven\ForReadingBadgeContextValues\ForReadingDailyDownloads;
use Badge\Application\Port\Driven\ForReadingBadgeContextValues\ForReadingDependents;
use Badge\Application\Port\Driven\ForReadingBadgeContextValues\ForReadingLicense;
use Badge\Application\Port\Driven\ForReadingBadgeContextValues\ForReadingMonthlyDownloads;
use Badge\Application\Port\Driven\ForReadingBadgeContextValues\ForReadingStableVersion;
use Badge\Application\Port\Driven\ForReadingBadgeContextValues\ForReadingSuggesters;
use Badge\Application\Port\Driven\ForReadingBadgeContextValues\ForReadingTotalDownloads;
use Badge\Application\Port\Driven\ForReadingBadgeContextValues\ForReadingUnstableVersion;

interface ContextValueReader extends
    ForReadingSuggesters,
    ForReadingDependents,
    ForReadingTotalDownloads,
    ForReadingMonthlyDownloads,
    ForReadingDailyDownloads,
    ForReadingStableVersion,
    ForReadingUnstableVersion,
    ForReadingLicense
{
}
