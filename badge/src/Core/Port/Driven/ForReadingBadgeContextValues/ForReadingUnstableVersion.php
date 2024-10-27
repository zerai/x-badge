<?php declare(strict_types=1);

namespace Badge\Core\Port\Driven\ForReadingBadgeContextValues;

interface ForReadingUnstableVersion
{
    /**
     * @param string $packageName full package name "[vendor]/[package]"
     */
    public function readUnstableVersion(string $packageName): string;
}
