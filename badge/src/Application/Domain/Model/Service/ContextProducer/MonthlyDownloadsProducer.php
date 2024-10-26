<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\MonthlyDownloads;
use Badge\Application\Port\Driven\ForReadingMonthlyDownloads;

final class MonthlyDownloadsProducer implements ContextProducer
{
    private ForReadingMonthlyDownloads $monthlyDownloadsReader;

    public function __construct(ForReadingMonthlyDownloads $monthlyDownloadsReader)
    {
        $this->monthlyDownloadsReader = $monthlyDownloadsReader;
    }

    public function contextFor(string $packageName): BadgeContext
    {
        return MonthlyDownloads::withCount($this->monthlyDownloadsReader->readMonthlyDownloads($packageName));
    }
}
