<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

interface DailyDownloadsReader
{
    /**
     * @param string $packageName full package name "[vendor]/[package]"
     */
    public function readDailyDownloads(string $packageName): int;
}
