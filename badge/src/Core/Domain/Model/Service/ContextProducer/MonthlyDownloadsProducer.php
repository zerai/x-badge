<?php declare(strict_types=1);

namespace Badge\Core\Domain\Model\Service\ContextProducer;

use Badge\Core\Domain\Model\BadgeContext;
use Badge\Core\Domain\Model\ContextValue\MonthlyDownloads;
use Badge\Core\Port\Driven\ForReadingBadgeContextValues\ForReadingMonthlyDownloads;

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
