<?php declare(strict_types=1);

namespace Badge\Application\PortOut;

use Badge\Application\Domain\Model\Service\ContextProducer\ForReadingDailyDownloads;
use Badge\Application\Domain\Model\Service\ContextProducer\ForReadingDependents;
use Badge\Application\Domain\Model\Service\ContextProducer\ForReadingLicense;
use Badge\Application\Domain\Model\Service\ContextProducer\ForReadingMonthlyDownloads;
use Badge\Application\Domain\Model\Service\ContextProducer\ForReadingStableVersion;
use Badge\Application\Domain\Model\Service\ContextProducer\ForReadingSuggesters;
use Badge\Application\Domain\Model\Service\ContextProducer\ForReadingTotalDownloads;
use Badge\Application\Domain\Model\Service\ContextProducer\UnstableVersionReader;

interface ContextValueReader extends
    ForReadingSuggesters,
    ForReadingDependents,
    ForReadingTotalDownloads,
    ForReadingMonthlyDownloads,
    ForReadingDailyDownloads,
    ForReadingStableVersion,
    UnstableVersionReader,
    ForReadingLicense
{
}
