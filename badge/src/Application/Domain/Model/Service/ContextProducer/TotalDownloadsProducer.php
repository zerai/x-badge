<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\TotalDownloads;
use Badge\Application\Port\Driven\ForReadingBadgeContextValues\ForReadingTotalDownloads;

final class TotalDownloadsProducer implements ContextProducer
{
    private ForReadingTotalDownloads $totalDownloadReader;

    public function __construct(ForReadingTotalDownloads $totalDownloadReader)
    {
        $this->totalDownloadReader = $totalDownloadReader;
    }

    public function contextFor(string $packageName): BadgeContext
    {
        return TotalDownloads::withCount($this->totalDownloadReader->readTotalDownloads($packageName));
    }
}
