<?php declare(strict_types=1);

namespace Badge\Core\Domain\Model\Service\ContextProducer;

use Badge\Core\Domain\Model\BadgeContext;
use Badge\Core\Domain\Model\ContextValue\TotalDownloads;
use Badge\Core\Port\Driven\ForReadingBadgeContextValues\ForReadingTotalDownloads;

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
