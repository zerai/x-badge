<?php declare(strict_types=1);

namespace Badge\Application\Port\Driven;

interface ForReadingMonthlyDownloads
{
    /**
     * @param string $packageName full package name "[vendor]/[package]"
     */
    public function readMonthlyDownloads(string $packageName): int;
}
