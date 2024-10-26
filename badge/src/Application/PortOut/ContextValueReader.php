<?php declare(strict_types=1);

namespace Badge\Application\PortOut;

use Badge\Application\Domain\Model\Service\ContextProducer\ForReadingMonthlyDownloads;
use Badge\Application\Domain\Model\Service\ContextProducer\ForReadingStableVersion;
use Badge\Application\Domain\Model\Service\ContextProducer\ForReadingSuggesters;
use Badge\Application\Domain\Model\Service\ContextProducer\ForReadingTotalDownloads;
use Badge\Application\Domain\Model\Service\ContextProducer\ForReadingUnstableVersion;
use Badge\Application\Port\Driven\ForReadingDailyDownloads;
use Badge\Application\Port\Driven\ForReadingDependents;
use Badge\Application\Port\Driven\ForReadingLicense;

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
