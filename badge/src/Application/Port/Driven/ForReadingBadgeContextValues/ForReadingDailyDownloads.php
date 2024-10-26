<?php declare(strict_types=1);

namespace Badge\Application\Port\Driven\ForReadingBadgeContextValues;

interface ForReadingDailyDownloads
{
    /**
     * @param string $packageName full package name "[vendor]/[package]"
     */
    public function readDailyDownloads(string $packageName): int;
}