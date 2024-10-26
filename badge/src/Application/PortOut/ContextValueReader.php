<?php declare(strict_types=1);

namespace Badge\Application\PortOut;

use Badge\Application\Port\Driven\ForReadingDailyDownloads;
use Badge\Application\Port\Driven\ForReadingDependents;
use Badge\Application\Port\Driven\ForReadingLicense;
use Badge\Application\Port\Driven\ForReadingMonthlyDownloads;
use Badge\Application\Port\Driven\ForReadingStableVersion;
use Badge\Application\Port\Driven\ForReadingSuggesters;
use Badge\Application\Port\Driven\ForReadingTotalDownloads;
use Badge\Application\Port\Driven\ForReadingUnstableVersion;

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
