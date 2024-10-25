<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

interface ForReadingTotalDownloads
{
    /**
     * @param string $packageName full package name "[vendor]/[package]"
     */
    public function readTotalDownloads(string $packageName): int;
}
