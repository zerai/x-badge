<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\TotalDownloads;

final class TotalDownloadsProducer implements ContextProducer
{
    private TotalDownloadsReader $totalDownloadReader;

    public function __construct(TotalDownloadsReader $totalDownloadReader)
    {
        $this->totalDownloadReader = $totalDownloadReader;
    }

    public function contextFor(string $packageName): BadgeContext
    {
        try {
            return new TotalDownloads($this->totalDownloadReader->readTotalDownloads($packageName));
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
