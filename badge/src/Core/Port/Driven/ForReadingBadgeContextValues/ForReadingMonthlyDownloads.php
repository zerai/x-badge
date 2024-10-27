<?php declare(strict_types=1);

namespace Badge\Core\Port\Driven\ForReadingBadgeContextValues;

interface ForReadingMonthlyDownloads
{
    /**
     * @param string $packageName full package name "[vendor]/[package]"
     */
    public function readMonthlyDownloads(string $packageName): int;
}
