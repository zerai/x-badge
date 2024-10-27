<?php declare(strict_types=1);

namespace Badge\Core\Domain\Model\Service\ContextProducer;

use Badge\Core\Domain\Model\BadgeContext;
use Badge\Core\Domain\Model\ContextValue\DailyDownloads;
use Badge\Core\Port\Driven\ForReadingBadgeContextValues\ForReadingDailyDownloads;

final class DailyDownloadsProducer implements ContextProducer
{
    private ForReadingDailyDownloads $dailyDownloadsReader;

    public function __construct(ForReadingDailyDownloads $dailyDownloadsReader)
    {
        $this->dailyDownloadsReader = $dailyDownloadsReader;
    }

    public function contextFor(string $packageName): BadgeContext
    {
        return DailyDownloads::withCount($this->dailyDownloadsReader->readDailyDownloads($packageName));
    }
}
