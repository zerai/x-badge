<?php declare(strict_types=1);

namespace Badge\Application\Port\Driven;

interface ForReadingStableVersion
{
    /**
     * @param string $packageName full package name "[vendor]/[package]"
     */
    public function readStableVersion(string $packageName): string;
}
