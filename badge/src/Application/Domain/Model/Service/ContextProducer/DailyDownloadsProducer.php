<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\DailyDownloads;

final class DailyDownloadsProducer implements ContextProducer
{
    private DailyDownloadsReader $dailyDownloadsReader;

    public function __construct(DailyDownloadsReader $dailyDownloadsReader)
    {
        $this->dailyDownloadsReader = $dailyDownloadsReader;
    }

    public function contextFor(string $packageName): BadgeContext
    {
        try {
            return new DailyDownloads($this->dailyDownloadsReader->readDailyDownloads($packageName));
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
